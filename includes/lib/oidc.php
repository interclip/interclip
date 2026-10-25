<?php

use Facile\JoseVerifier\JWK\JwksProviderBuilder;
use Facile\OpenIDClient\Client\ClientBuilder;
use Facile\OpenIDClient\Client\ClientInterface;
use Facile\OpenIDClient\Client\Metadata\ClientMetadata;
use Facile\OpenIDClient\Issuer\Issuer;
use Facile\OpenIDClient\Issuer\Metadata\IssuerMetadata;
use Facile\OpenIDClient\Service\Builder\AuthorizationServiceBuilder;
use Facile\OpenIDClient\Session\AuthSession;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Client\ClientInterface as HttpClient;

require_once __DIR__ . '/../anti-csrf.php';

/** Scope an immutable subject to its issuer without changing legacy account keys. */
function oidcAccountSubject(string $issuer, string $subject): string
{
    if ($issuer === '' || $subject === '') {
        throw new RuntimeException('Missing OIDC identity.');
    }
    return 'oidc|' . hash('sha256', json_encode([$issuer, $subject], JSON_THROW_ON_ERROR));
}

// Where is Zod for PHP? 😭
function oidcClaimsValid(object $claims, string $issuer, string $clientId, string $nonce, int $now): bool
{
    $audience = $claims->aud ?? null;
    return ($claims->iss ?? null) === $issuer
        && is_string($claims->sub ?? null) && $claims->sub !== '' && strlen($claims->sub) <= 255
        && (is_string($audience) ? $audience === $clientId : (is_array($audience) && in_array($clientId, $audience, true)))
        && (!isset($claims->azp) || $claims->azp === $clientId)
        && (!is_array($audience) || count($audience) <= 1 || ($claims->azp ?? null) === $clientId)
        && $nonce !== '' && is_string($claims->nonce ?? null) && hash_equals($nonce, $claims->nonce)
        && is_int($claims->exp ?? null) && $claims->exp > $now
        && is_int($claims->iat ?? null) && $claims->iat <= $now + 60 && $claims->iat < $claims->exp
        && (!isset($claims->nbf) || (is_int($claims->nbf) && $claims->nbf <= $now + 60));
}

/** A small adapter around the library's discovery, authorization and token validation. */
final class InterclipOidcClient
{
    private HttpClient $http;
    private ?ClientInterface $client = null;

    public function __construct(
        private string $issuer,
        private string $clientId,
        private string $clientSecret,
        private string $redirectUri,
        ?HttpClient $http = null
    ) {
        $this->http = $http ?? new Client(['timeout' => 10, 'connect_timeout' => 5, 'allow_redirects' => false]);
    }

    public function getIssuer(): string { return $this->issuer; }
    public function getClientID(): string { return $this->clientId; }

    private function client(): ClientInterface
    {
        if ($this->client !== null) {
            return $this->client;
        }
        $response = $this->http->sendRequest(new Request('GET', rtrim($this->issuer, '/') . '/.well-known/openid-configuration'));
        if ($response->getStatusCode() !== 200) {
            throw new RuntimeException('Unable to fetch OIDC discovery.');
        }
        $metadata = json_decode((string) $response->getBody(), true, 32, JSON_THROW_ON_ERROR);
        if (!is_array($metadata) || ($metadata['issuer'] ?? null) !== $this->issuer
            || !in_array('S256', $metadata['code_challenge_methods_supported'] ?? [], true)
            || !in_array('client_secret_basic', $metadata['token_endpoint_auth_methods_supported'] ?? ['client_secret_basic'], true)) {
            throw new RuntimeException('OIDC discovery must match the issuer and support S256 PKCE and client_secret_basic.');
        }
        foreach (['authorization_endpoint', 'token_endpoint', 'jwks_uri'] as $name) {
            $endpoint = parse_url((string) ($metadata[$name] ?? ''));
            if (!is_array($endpoint) || ($endpoint['scheme'] ?? '') !== 'https'
                || empty($endpoint['host']) || isset($endpoint['user']) || isset($endpoint['pass'])
                || isset($endpoint['fragment'])) {
                throw new RuntimeException('OIDC endpoints must use HTTPS.');
            }
        }
        $keys = (new JwksProviderBuilder())->withHttpClient($this->http)
            ->withRequestFactory(new HttpFactory())->withJwksUri($metadata['jwks_uri'])->build();
        return $this->client = (new ClientBuilder())
            ->setHttpClient($this->http)
            ->setIssuer(new Issuer(IssuerMetadata::fromArray($metadata), $keys))
            ->setClientMetadata(ClientMetadata::fromArray([
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'token_endpoint_auth_method' => 'client_secret_basic',
                'id_token_signed_response_alg' => 'RS256',
                'redirect_uris' => [$this->redirectUri],
                'response_types' => ['code'],
                'grant_types' => ['authorization_code'],
            ]))->build();
    }

    private function service(): \Facile\OpenIDClient\Service\AuthorizationService
    {
        return (new AuthorizationServiceBuilder())->setHttpClient($this->http)
            ->setRequestFactory(new HttpFactory())->build();
    }

    public function authorizationUrl(array &$session): string
    {
        $session['oidc_login_started'] = time();
        $session['oidc_state'] = bin2hex(random_bytes(32));
        $session['oidc_nonce'] = bin2hex(random_bytes(32));
        $session['oidc_code_verifier'] = bin2hex(random_bytes(32));
        $challenge = rtrim(strtr(base64_encode(hash('sha256', $session['oidc_code_verifier'], true)), '+/', '-_'), '=');
        return $this->service()->getAuthorizationUri($this->client(), [
            'scope' => 'openid profile email',
            'state' => $session['oidc_state'],
            'nonce' => $session['oidc_nonce'],
            'code_challenge_method' => 'S256',
            'code_challenge' => $challenge,
        ]);
    }

    public function exchange(array $query, array $session): object
    {
        if (!oidcCallbackValid($query, $session, time())) {
            throw new RuntimeException('Invalid or expired OIDC login attempt.');
        }
        $tokens = $this->service()->callback($this->client(), [
            'code' => $query['code'], 'state' => $query['state'],
        ], $this->redirectUri, AuthSession::fromArray([
            'state' => $session['oidc_state'], 'nonce' => $session['oidc_nonce'],
            'code_verifier' => $session['oidc_code_verifier'],
        ]));
        $claims = (object) $tokens->claims();
        if ($tokens->getIdToken() === null
            || !oidcClaimsValid($claims, $this->issuer, $this->clientId, $session['oidc_nonce'], time())) {
            throw new RuntimeException('Invalid OIDC ID token.');
        }
        return $claims;
    }
}

function createOidcClient(string $redirectUri): InterclipOidcClient
{
    $issuer = $_ENV['OIDC_ISSUER'] ?? '';
    $parts = parse_url($issuer);
    if (!is_array($parts) || ($parts['scheme'] ?? '') !== 'https' || empty($parts['host'])
        || isset($parts['user']) || isset($parts['pass']) || isset($parts['query']) || isset($parts['fragment'])
        || empty($_ENV['OIDC_CLIENT_ID']) || empty($_ENV['OIDC_CLIENT_SECRET'])) {
        throw new RuntimeException('Configure an HTTPS OIDC_ISSUER, OIDC_CLIENT_ID and OIDC_CLIENT_SECRET.');
    }
    return new InterclipOidcClient($issuer, $_ENV['OIDC_CLIENT_ID'], $_ENV['OIDC_CLIENT_SECRET'], $redirectUri);
}

function clearOidcLoginAttempt(): void
{
    unset($_SESSION['oidc_login_started'], $_SESSION['oidc_state'],
        $_SESSION['oidc_nonce'], $_SESSION['oidc_code_verifier']);
}

/** Check the browser transaction before making any token request. */
function oidcCallbackValid(array $query, array $session, int $now): bool
{
    return !isset($query['error'])
        && is_string($query['code'] ?? null) && $query['code'] !== '' && strlen($query['code']) <= 4096
        && is_string($query['state'] ?? null) && $query['state'] !== ''
        && is_string($session['oidc_state'] ?? null)
        && hash_equals($session['oidc_state'], $query['state'])
        && is_string($session['oidc_nonce'] ?? null) && $session['oidc_nonce'] !== ''
        && is_string($session['oidc_code_verifier'] ?? null) && $session['oidc_code_verifier'] !== ''
        && is_int($session['oidc_login_started'] ?? null)
        && $session['oidc_login_started'] <= $now && $session['oidc_login_started'] > $now - 600;
}

function oidcSessionUser(array $session, string $issuer, string $clientId, int $now): array|false
{
    $identity = $session['oidc_identity'] ?? null;
    if (!is_array($identity) || ($identity['issuer'] ?? null) !== $issuer
        || ($identity['client_id'] ?? null) !== $clientId
        || !is_int($identity['expires'] ?? null) || $identity['expires'] <= $now
        || !is_array($identity['user'] ?? null)) {
        return false;
    }
    return $identity['user'];
}

function completeOidcLogin(InterclipOidcClient $client): void
{
    $claims = $client->exchange($_GET, $_SESSION);
    $email = $claims->email ?? null;
    if (!is_string($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        throw new RuntimeException('OIDC identity is missing an email address.');
    }
    $user = [
        'sub' => $claims->sub,
        'email' => $email,
        'email_verified' => ($claims->email_verified ?? false) === true,
        'name' => is_string($claims->name ?? null) ? $claims->name : 'Interclip user',
    ];
    session_regenerate_id(true);
    $_SESSION = [
        'oidc_identity' => [
            'issuer' => $client->getIssuer(),
            'client_id' => $client->getClientID(),
            'expires' => min($claims->exp, time() + 3600),
            'user' => $user,
        ],
    ];
    store();
}
