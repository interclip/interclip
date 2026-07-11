<?php

use League\Uri\Contracts\UriException;
use League\Uri\UriString;

const CLIP_URL_MAX_LENGTH = 2048;
const CLIP_CODE_LENGTH = 5;
const CLIP_CODE_ALPHABET = '0123456789abcdefghijklmnopqrstuvwxyz';
const CLIP_RESERVED_CODES = ['admin', 'about', 'login', 'tests'];
const CLIP_TTL_SECONDS = 2 * 24 * 60 * 60;

/**
 * Parse an absolute RFC 3986 URI without changing its representation.
 *
 * @return array{scheme: string, user: ?string, pass: ?string, host: ?string, port: ?int, path: string, query: ?string, fragment: ?string}|null
 */
function parseClipUri(string $uri): ?array
{
    if (
        $uri === ''
        || strlen($uri) > CLIP_URL_MAX_LENGTH
        || !UriString::containsRfc3986Chars($uri)
    ) {
        return null;
    }

    try {
        $parts = UriString::parse($uri);
    } catch (UriException) {
        return null;
    }

    if (!is_string($parts['scheme']) || $parts['scheme'] === '') {
        return null;
    }

    $userinfoPattern = "/\A(?:[A-Za-z0-9._~!\$&'()*+,;=:-]|%[A-Fa-f0-9]{2})*\z/D";
    $pathPattern = "/\A(?:[A-Za-z0-9._~!\$&'()*+,;=:@\/-]|%[A-Fa-f0-9]{2})*\z/D";
    $queryOrFragmentPattern = "/\A(?:[A-Za-z0-9._~!\$&'()*+,;=:@\/?-]|%[A-Fa-f0-9]{2})*\z/D";

    if (
        ($parts['user'] !== null && preg_match($userinfoPattern, $parts['user']) !== 1)
        || ($parts['pass'] !== null && preg_match($userinfoPattern, $parts['pass']) !== 1)
        || preg_match($pathPattern, $parts['path']) !== 1
        || ($parts['query'] !== null && preg_match($queryOrFragmentPattern, $parts['query']) !== 1)
        || ($parts['fragment'] !== null && preg_match($queryOrFragmentPattern, $parts['fragment']) !== 1)
    ) {
        return null;
    }

    /** @var array{scheme: string, user: ?string, pass: ?string, host: ?string, port: ?int, path: string, query: ?string, fragment: ?string} $parts */
    return $parts;
}

/**
 * Validate a clip destination without changing its representation.
 */
function normalizeClipUrl(string $url): ?string
{
    return parseClipUri($url) === null ? null : $url;
}

/**
 * Executable URI schemes are valid clipboard contents but must not become
 * active links or redirects from the Interclip origin.
 */
function isSafeNavigationUri(string $uri): bool
{
    $parts = parseClipUri($uri);

    return $parts !== null
        && in_array(strtolower($parts['scheme']), ['http', 'https'], true)
        && is_string($parts['host'])
        && $parts['host'] !== ''
        && $parts['user'] === null
        && $parts['pass'] === null
        && filter_var($uri, FILTER_VALIDATE_URL) !== false;
}

/**
 * Accept the platform's five-character, case-insensitive base36 codes.
 */
function isValidClipCode(string $code): bool
{
    return preg_match('/\A[A-Za-z0-9]{5}\z/D', $code) === 1
        && !in_array(strtolower($code), CLIP_RESERVED_CODES, true);
}

/**
 * Return the canonical lowercase representation of a valid clip code.
 */
function normalizeClipCode(string $code): ?string
{
    return isValidClipCode($code) ? strtolower($code) : null;
}

/**
 * Generate a five-character lowercase base36 code without modulo bias.
 */
function generateClipCode(): string
{
    $alphabetLength = strlen(CLIP_CODE_ALPHABET);
    $largestUnbiasedByte = intdiv(256, $alphabetLength) * $alphabetLength;

    do {
        $code = '';
        while (strlen($code) < CLIP_CODE_LENGTH) {
            foreach (unpack('C*', random_bytes(CLIP_CODE_LENGTH)) as $byte) {
                if ($byte >= $largestUnbiasedByte) {
                    continue;
                }

                $code .= CLIP_CODE_ALPHABET[$byte % $alphabetLength];
                if (strlen($code) === CLIP_CODE_LENGTH) {
                    break;
                }
            }
        }
    } while (!isValidClipCode($code));

    return $code;
}

/**
 * Detect HTTPS either at the web server or through one explicitly trusted
 * TLS-terminating proxy.
 */
function requestUsesHttps(): bool
{
    $httpsValue = strtolower(trim((string) ($_SERVER['HTTPS'] ?? '')));
    if (!in_array($httpsValue, ['', 'off', '0'], true)) {
        return true;
    }

    $remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if (
        !is_string($remoteAddress)
        || filter_var($remoteAddress, FILTER_VALIDATE_IP) === false
        || !isTrustedProxy($remoteAddress)
    ) {
        return false;
    }

    $forwardedProto = $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '';

    return is_string($forwardedProto)
        && strtolower(trim($forwardedProto)) === 'https';
}

/**
 * Escape a value for an HTML text or quoted-attribute context.
 */
function escapeHtml(mixed $value): string
{
    if (!is_scalar($value) && !$value instanceof Stringable && $value !== null) {
        return '';
    }

    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5,
        'UTF-8'
    );
}

/**
 * Return the exact UTC expiration instant for a newly created clip.
 */
function clipExpirationDateTime(?DateTimeImmutable $createdAt = null): DateTimeImmutable
{
    $utc = new DateTimeZone('UTC');
    $createdAt ??= new DateTimeImmutable('now', $utc);

    return $createdAt
        ->setTimezone($utc)
        ->modify('+' . CLIP_TTL_SECONDS . ' seconds');
}

/**
 * Resolve a UTC DATETIME(6) value without discarding its microseconds.
 */
function clipExpiryMicroseconds(string $expiryDateTime): ?int
{
    $date = DateTimeImmutable::createFromFormat(
        '!Y-m-d H:i:s.u',
        $expiryDateTime,
        new DateTimeZone('UTC')
    );
    $errors = DateTimeImmutable::getLastErrors();

    if (
        $date === false
        || ($errors !== false && ($errors['warning_count'] > 0 || $errors['error_count'] > 0))
        || $date->format('Y-m-d H:i:s.u') !== $expiryDateTime
    ) {
        return null;
    }

    return dateTimeUnixMicroseconds($date);
}

function dateTimeUnixMicroseconds(DateTimeInterface $date): int
{
    return ((int) $date->format('U') * 1_000_000) + (int) $date->format('u');
}

/**
 * Check an IP address against an exact address or CIDR range.
 */
function ipMatchesTrustedRange(string $ip, string $range): bool
{
    $range = trim($range);
    if ($range === '') {
        return false;
    }

    if (!str_contains($range, '/')) {
        return hash_equals($range, $ip);
    }

    [$subnet, $prefixString] = explode('/', $range, 2);
    if ($prefixString === '' || preg_match('/\A\d{1,3}\z/D', $prefixString) !== 1) {
        return false;
    }

    $packedIp = @inet_pton($ip);
    $packedSubnet = @inet_pton($subnet);
    if ($packedIp === false || $packedSubnet === false || strlen($packedIp) !== strlen($packedSubnet)) {
        return false;
    }

    $prefix = (int) $prefixString;
    $maximumPrefix = strlen($packedIp) * 8;
    if ($prefix < 0 || $prefix > $maximumPrefix) {
        return false;
    }

    $wholeBytes = intdiv($prefix, 8);
    $remainingBits = $prefix % 8;

    if ($wholeBytes > 0 && substr($packedIp, 0, $wholeBytes) !== substr($packedSubnet, 0, $wholeBytes)) {
        return false;
    }

    if ($remainingBits === 0) {
        return true;
    }

    $mask = (0xff << (8 - $remainingBits)) & 0xff;

    return (ord($packedIp[$wholeBytes]) & $mask) === (ord($packedSubnet[$wholeBytes]) & $mask);
}

function isValidTrustedProxyRange(string $range): bool
{
    $range = trim($range);
    if ($range === '') {
        return false;
    }

    if (!str_contains($range, '/')) {
        return filter_var($range, FILTER_VALIDATE_IP) !== false;
    }

    [$subnet, $prefixString] = explode('/', $range, 2);
    $packedSubnet = @inet_pton($subnet);
    if (
        $packedSubnet === false
        || preg_match('/\A\d{1,3}\z/D', $prefixString) !== 1
    ) {
        return false;
    }

    $prefix = (int) $prefixString;
    $maximumPrefix = strlen($packedSubnet) * 8;

    return $prefix > 0 && $prefix <= $maximumPrefix;
}

function isTrustedProxy(string $ip): bool
{
    $configured = $_ENV['TRUSTED_PROXIES'] ?? getenv('TRUSTED_PROXIES');
    if (!is_string($configured) || trim($configured) === '') {
        return false;
    }

    foreach (explode(',', $configured) as $range) {
        if (ipMatchesTrustedRange($ip, $range)) {
            return true;
        }
    }

    return false;
}

/**
 * Return the nearest untrusted address in a trusted proxy chain.
 */
function clientIp(): string
{
    $remoteAddress = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!is_string($remoteAddress) || filter_var($remoteAddress, FILTER_VALIDATE_IP) === false) {
        return 'unknown';
    }

    if (!isTrustedProxy($remoteAddress)) {
        return $remoteAddress;
    }

    $forwardedFor = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? '';
    if (!is_string($forwardedFor) || trim($forwardedFor) === '') {
        return $remoteAddress;
    }

    $chain = [];
    foreach (explode(',', $forwardedFor) as $address) {
        $address = trim($address);
        if (filter_var($address, FILTER_VALIDATE_IP) === false) {
            return $remoteAddress;
        }
        $chain[] = $address;
    }
    $chain[] = $remoteAddress;

    for ($index = count($chain) - 1; $index >= 0; $index--) {
        if (!isTrustedProxy($chain[$index])) {
            return $chain[$index];
        }
    }

    return $remoteAddress;
}
