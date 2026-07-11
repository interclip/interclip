#!/usr/bin/env bash

set -euo pipefail

if [[ ! -f vendor/autoload.php || ! -f out/index.js || ! -f css/index.css ]]; then
    echo "Interclip dependencies or built assets are missing. Run: bash .devcontainer/post-create.sh" >&2
    exit 1
fi

for attempt in $(seq 1 60); do
    if MYSQL_PWD="${PASSWORD}" mysqladmin \
        --host="${DB_SERVER%%:*}" \
        --port="${DB_SERVER##*:}" \
        --user="${USERNAME}" \
        --silent ping >/dev/null 2>&1; then
        break
    fi

    if [[ "${attempt}" -eq 60 ]]; then
        echo "MySQL did not become ready." >&2
        exit 1
    fi
    sleep 1
done

for attempt in $(seq 1 60); do
    if REDISCLI_AUTH="${REDIS_PASSWORD}" redis-cli \
        -h "${REDIS_HOST}" \
        -p "${REDIS_PORT}" \
        ping 2>/dev/null | grep -q '^PONG$'; then
        break
    fi

    if [[ "${attempt}" -eq 60 ]]; then
        echo "Redis did not become ready." >&2
        exit 1
    fi
    sleep 1
done

for attempt in $(seq 1 60); do
    if curl --fail --silent --output /dev/null http://localhost:8080/; then
        echo "Interclip is ready at http://localhost:8080"
        exit 0
    fi

    if [[ "${attempt}" -eq 60 ]]; then
        echo "Apache did not serve Interclip successfully." >&2
        exit 1
    fi
    sleep 1
done
