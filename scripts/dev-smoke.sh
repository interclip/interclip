#!/usr/bin/env bash

set -euo pipefail

base_url="${INTERCLIP_BASE_URL:-http://localhost:8080}"
work_dir="$(mktemp -d)"
created_codes=()
smoke_rate_keys=()

db_host="${DB_SERVER%%:*}"
db_port="${DB_SERVER##*:}"
if [[ "${db_host}" == "${db_port}" ]]; then
    db_port=3306
fi

fail() {
    echo "Dev smoke test failed: $*" >&2
    exit 1
}

mysql_query() {
    MYSQL_PWD="${PASSWORD}" mysql \
        --host="${db_host}" \
        --port="${db_port}" \
        --user="${USERNAME}" \
        --database="${DB_NAME}" \
        --batch \
        --skip-column-names \
        --raw \
        --execute="$1"
}

redis_command() {
    REDISCLI_AUTH="${REDIS_PASSWORD}" redis-cli \
        -h "${REDIS_HOST}" \
        -p "${REDIS_PORT}" \
        --raw \
        "$@"
}

cleanup() {
    local status=$?
    set +e

    for clip_code in "${created_codes[@]:-}"; do
        if [[ -n "${clip_code}" ]]; then
            mysql_query "DELETE FROM userurl WHERE usr = '${clip_code}'" >/dev/null 2>&1
            redis_command DEL "${REDIS_KEY_PREFIX}:clip:${clip_code}" >/dev/null 2>&1
        fi
    done

    if (( ${#smoke_rate_keys[@]} > 0 )); then
        redis_command DEL "${smoke_rate_keys[@]}" >/dev/null 2>&1
    fi

    rm -rf "${work_dir}"
    return "${status}"
}
trap cleanup EXIT

for required_name in \
    AUTH_TYPE \
    DB_NAME \
    DB_SERVER \
    PASSWORD \
    REDIS_HOST \
    REDIS_KEY_PREFIX \
    REDIS_PASSWORD \
    REDIS_PORT \
    USERNAME; do
    [[ -n "${!required_name:-}" ]] || fail "${required_name} is not configured"
done

smoke_client_hash="$(php -r 'echo hash_hmac("sha256", "127.0.0.1", (string) getenv("IP_HASH_KEY"));')"
smoke_rate_keys=(
    "${REDIS_KEY_PREFIX}:rate:clip-create:${smoke_client_hash}"
    "${REDIS_KEY_PREFIX}:rate:clip-read:${smoke_client_hash}"
)
redis_command DEL "${smoke_rate_keys[@]}" >/dev/null

bash .devcontainer/post-start.sh >/dev/null
initial_active_total="$(mysql_query "SELECT COUNT(*) FROM userurl WHERE expires_at > UTC_TIMESTAMP(6)")"
[[ "${initial_active_total}" =~ ^[0-9]+$ ]] || fail "the active clip count is unavailable"

for asset in / /out/index.js /css/index.css; do
    curl --fail --silent --show-error --output /dev/null "${base_url}${asset}" \
        || fail "${asset} was not served"
done

for private_path in /.env /composer.json /public/index.php; do
    private_status="$(curl --silent --show-error --output /dev/null --write-out '%{http_code}' "${base_url}${private_path}")"
    if [[ "${private_status}" != "403" && "${private_status}" != "404" ]]; then
        fail "${private_path} returned ${private_status} instead of 403/404"
    fi
done

nonce="$(date +%s)-$$-${RANDOM}"
api_url="${base_url}/about/?dev-smoke-api=${nonce}"
api_payload="$(jq --null-input --compact-output --arg url "${api_url}" '{url: $url}')"
api_response="$(
    curl --fail-with-body --silent --show-error \
        --header 'Content-Type: application/json' \
        --data "${api_payload}" \
        "${base_url}/api/set"
)"
api_code="$(
    jq --exit-status --raw-output \
        'select(.status == "success") | .result | select(test("^[a-z0-9]{5}$"))' \
        <<<"${api_response}"
)" || fail "the JSON create endpoint did not return a five-character clip code"
created_codes+=("${api_code}")

stored_url="$(mysql_query "SELECT url FROM userurl WHERE usr = '${api_code}' AND expires_at > UTC_TIMESTAMP(6) LIMIT 1")"
[[ "${stored_url}" == "${api_url}" ]] || fail "the created clip was not stored in MySQL"
expiry_microseconds="$(mysql_query "SELECT TIMESTAMPDIFF(MICROSECOND, CAST(date AS DATETIME(6)), expires_at) FROM userurl WHERE usr = '${api_code}' LIMIT 1")"
[[ "${expiry_microseconds}" == "172800000000" ]] || fail "the database expiry was not exactly 48 hours after creation"
active_total_after_create="$(mysql_query "SELECT COUNT(*) FROM userurl WHERE expires_at > UTC_TIMESTAMP(6)")"
[[ "${active_total_after_create}" -eq "$((initial_active_total + 1))" ]] \
    || fail "clip creation did not increment the active clip count"

redis_key="${REDIS_KEY_PREFIX}:clip:${api_code}"
cached_clip="$(redis_command GET "${redis_key}")"
jq --exit-status --arg url "${api_url}" \
    'select(.url == $url and (.expires | type == "number"))' \
    <<<"${cached_clip}" >/dev/null \
    || fail "the created clip was not cached in Redis"
cache_ttl_ms="$(redis_command PTTL "${redis_key}")"
(( cache_ttl_ms > 172790000 && cache_ttl_ms <= 172800000 )) \
    || fail "the Redis cache TTL did not match the 48-hour database expiry"

lookup_response="$(curl --fail-with-body --silent --show-error "${base_url}/api/get?code=${api_code}")"
jq --exit-status --arg url "${api_url}" \
    'select(.status == "success" and .result == $url)' \
    <<<"${lookup_response}" >/dev/null \
    || fail "the JSON lookup endpoint returned the wrong URL"

redis_command DEL "${redis_key}" >/dev/null
uppercase_api_code="$(tr '[:lower:]' '[:upper:]' <<<"${api_code}")"
fallback_response="$(curl --fail-with-body --silent --show-error "${base_url}/api/get?code=${uppercase_api_code}")"
jq --exit-status --arg url "${api_url}" \
    'select(.status == "success" and .result == $url)' \
    <<<"${fallback_response}" >/dev/null \
    || fail "case-insensitive lookup did not fall back to MySQL"
[[ -n "$(redis_command GET "${redis_key}")" ]] \
    || fail "the MySQL fallback did not repopulate Redis"

redirect_result="$(
    curl --silent --show-error --output /dev/null \
        --write-out $'%{http_code}\n%{redirect_url}' \
        "${base_url}/${uppercase_api_code}"
)"
redirect_status="${redirect_result%%$'\n'*}"
redirect_location="${redirect_result#*$'\n'}"
[[ "${redirect_status}" == "302" ]] || fail "the direct clip route did not return 302"
[[ "${redirect_location}" == "${api_url}" ]] || fail "the direct clip route returned the wrong Location"

inert_uri="https://user:password@example.com/private"
inert_payload="$(jq --null-input --compact-output --arg url "${inert_uri}" '{url: $url}')"
inert_response="$(
    curl --fail-with-body --silent --show-error \
        --header 'Content-Type: application/json' \
        --data "${inert_payload}" \
        "${base_url}/api/set"
)"
inert_code="$(
    jq --exit-status --raw-output \
        'select(.status == "success") | .result | select(test("^[a-z0-9]{5}$"))' \
        <<<"${inert_response}"
)" || fail "the API rejected a valid credential-bearing URI"
created_codes+=("${inert_code}")

inert_lookup="$(curl --fail-with-body --silent --show-error "${base_url}/api/get?code=${inert_code}")"
jq --exit-status --arg url "${inert_uri}" \
    'select(.status == "success" and .result == $url)' \
    <<<"${inert_lookup}" >/dev/null \
    || fail "the API did not round-trip the credential-bearing URI"

inert_headers="${work_dir}/inert-headers.txt"
inert_body="${work_dir}/inert-body.txt"
inert_status="$(
    curl --silent --show-error \
        --dump-header "${inert_headers}" \
        --output "${inert_body}" \
        --write-out '%{http_code}' \
        "${base_url}/${inert_code}"
)"
[[ "${inert_status}" == "200" ]] || fail "the inert URI route did not return 200"
[[ "$(<"${inert_body}")" == "${inert_uri}" ]] || fail "the inert URI route returned the wrong body"
grep --ignore-case --quiet '^content-type: text/plain' "${inert_headers}" \
    || fail "the inert URI route did not use text/plain"
if grep --ignore-case --quiet '^location:' "${inert_headers}"; then
    fail "the inert URI route emitted a Location header"
fi

missing_csrf_status="$(
    curl --silent --show-error --output /dev/null --write-out '%{http_code}' \
        --data-urlencode "input=${api_url}" \
        "${base_url}/set"
)"
[[ "${missing_csrf_status}" == "403" ]] || fail "the browser create route accepted a missing CSRF token"

logout_get_status="$(curl --silent --show-error --output /dev/null --write-out '%{http_code}' "${base_url}/logout")"
[[ "${logout_get_status}" == "405" ]] || fail "GET /logout did not return 405"

cookie_jar="${work_dir}/cookies.txt"
home_html="${work_dir}/home.html"
curl --fail --silent --show-error \
    --cookie-jar "${cookie_jar}" \
    --output "${home_html}" \
    "${base_url}/"
csrf_token="$(sed -n 's/.*name="token" value="\([^"]*\)".*/\1/p' "${home_html}" | head -n 1)"
[[ "${csrf_token}" =~ ^[a-f0-9]{64}$ ]] || fail "the homepage did not provide a valid CSRF token"

form_url="${base_url}/privacy/?dev-smoke-form=${nonce}"
form_create_html="${work_dir}/form-create.html"
curl --fail-with-body --silent --show-error \
    --cookie "${cookie_jar}" \
    --cookie-jar "${cookie_jar}" \
    --data-urlencode "token=${csrf_token}" \
    --data-urlencode "input=${form_url}" \
    --output "${form_create_html}" \
    "${base_url}/set"
form_code="$(sed -n 's/^[[:space:]]*const code = "\([A-Za-z0-9]*\)";.*/\1/p' "${form_create_html}" | head -n 1)"
[[ "${form_code}" =~ ^[a-z0-9]{5}$ ]] \
    || fail "the CSRF-protected create form did not create a lowercase base36 clip code"
created_codes+=("${form_code}")

form_get_html="${work_dir}/form-get.html"
curl --fail-with-body --silent --show-error \
    --cookie "${cookie_jar}" \
    --cookie-jar "${cookie_jar}" \
    --data-urlencode "token=${csrf_token}" \
    --data-urlencode "user=${form_code}" \
    --output "${form_get_html}" \
    "${base_url}/get"
grep --fixed-strings --quiet "href=\"${form_url}\"" "${form_get_html}" \
    || fail "the CSRF-protected retrieve form returned the wrong URL"

inert_form_html="${work_dir}/inert-form-get.html"
curl --fail-with-body --silent --show-error \
    --cookie "${cookie_jar}" \
    --cookie-jar "${cookie_jar}" \
    --data-urlencode "token=${csrf_token}" \
    --data-urlencode "user=${inert_code}" \
    --output "${inert_form_html}" \
    "${base_url}/get"
grep --fixed-strings --quiet "<span id=\"urlLink\">${inert_uri}</span>" "${inert_form_html}" \
    || fail "the retrieve form did not render the inert URI as text"
if grep --fixed-strings --quiet '<a id="urlLink"' "${inert_form_html}"; then
    fail "the retrieve form rendered the inert URI as an active link"
fi

mysql_query "DELETE FROM userurl WHERE usr = '${inert_code}'" >/dev/null
redis_command DEL "${REDIS_KEY_PREFIX}:clip:${inert_code}" >/dev/null
reuse_url="${base_url}/about/?dev-smoke-reuse=${nonce}"
mysql_query "INSERT INTO userurl (usr, url, date, expires_at) VALUES ('${inert_code}', '${reuse_url}', UTC_TIMESTAMP(6), UTC_TIMESTAMP(6) + INTERVAL 48 HOUR)" >/dev/null
reused_url="$(mysql_query "SELECT url FROM userurl WHERE usr = '${inert_code}' LIMIT 1")"
[[ "${reused_url}" == "${reuse_url}" ]] \
    || fail "a released clip code could not be reused"

auth_summary="external auth skipped"
if [[ "${AUTH_TYPE}" == "mock" ]]; then
    login_result="$(
        curl --silent --show-error --output /dev/null \
            --write-out $'%{http_code}\n%{redirect_url}' \
            "${base_url}/login"
    )"
    login_status="${login_result%%$'\n'*}"
    login_location="${login_result#*$'\n'}"
    [[ "${login_status}" == "302" && "${login_location}" == "${base_url}/" ]] \
        || fail "mock login did not redirect to the homepage"

    mock_role="$(mysql_query "SELECT role FROM accounts WHERE subject = 'mock|local-development' LIMIT 1")"
    [[ "${mock_role}" == "visitor" ]] || fail "mock login did not provision a visitor account"

    admin_status="$(curl --silent --show-error --output /dev/null --write-out '%{http_code}' "${base_url}/admin")"
    [[ "${admin_status}" == "403" ]] || fail "the mock visitor could access the staff dashboard"
    auth_summary="mock login and visitor authorization"
else
    echo "Skipping deterministic login assertions because AUTH_TYPE=${AUTH_TYPE}."
fi

echo "Dev smoke test passed: API, browser forms, MySQL, Redis, redirect, CSRF, and ${auth_summary}."
