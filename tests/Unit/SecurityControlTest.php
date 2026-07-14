<?php

if (!defined('ROOT')) {
    define('ROOT', '');
}

require_once dirname(__DIR__, 2) . '/includes/anti-csrf.php';

it('accepts only the current unexpired CSRF token', function () {
    startSecureSession();
    $_SESSION = [];
    $token = store();

    expect(validateCsrfToken($token))->toBeTrue()
        ->and(validateCsrfToken(null))->toBeFalse()
        ->and(validateCsrfToken(str_repeat('0', 64)))->toBeFalse();

    $_SESSION['token-expire'] = time() - 1;
    expect(validateCsrfToken($token))->toBeFalse();
});

it('checks CSRF before creating a browser-submitted clip', function () {
    $controller = file_get_contents(dirname(__DIR__, 2) . '/public/core/set.php');

    expect(strpos($controller, 'validate();'))
        ->toBeLessThan(strpos($controller, 'createClip($url)'));
});

it('sets browser clip error statuses before rendering output', function () {
    foreach (['set.php', 'get.php'] as $controllerName) {
        $controller = file_get_contents(dirname(__DIR__, 2) . '/public/core/' . $controllerName);
        expect(strpos($controller, 'http_response_code('))->toBeLessThan(strpos($controller, '<!DOCTYPE html>'));
    }
});

it('performs direct clip lookup only in the front controller', function () {
    $router = file_get_contents(dirname(__DIR__, 2) . '/router.php');
    $errorPage = file_get_contents(dirname(__DIR__, 2) . '/includes/error.php');

    expect($router)->toContain("require ROOT_DIR . '/includes/components/get.php'")
        ->and($errorPage)->not()->toContain('includes/components/get.php');
});

it('reuses an active clip code for the same normalized URI', function () {
    $clipCreation = file_get_contents(dirname(__DIR__, 2) . '/includes/components/new.php');
    $clipLookup = file_get_contents(dirname(__DIR__, 2) . '/includes/components/get.php');

    expect($clipCreation)->toContain('findActiveClipForUrl')
        ->and($clipCreation)->toContain(
            'AND (expires_at IS NULL OR expires_at > UTC_TIMESTAMP(6))'
        )
        ->and($clipLookup)->toContain(
            'AND (expires_at IS NULL OR expires_at > UTC_TIMESTAMP(6))'
        )
        ->and($clipCreation)->toContain('if ($expiresAt === null)')
        ->and($clipLookup)->toContain('if ($expiresAt !== null)')
        ->and($clipCreation)->toContain('SELECT GET_LOCK(?, ?) AS acquired')
        ->and(strpos($clipCreation, 'acquireClipUriLock($connection, $normalizedUrl)'))
        ->toBeLessThan(strpos($clipCreation, '$existingClip = findActiveClipForUrl'))
        ->and(strpos($clipCreation, '$existingClip = findActiveClipForUrl'))
        ->toBeLessThan(strpos($clipCreation, 'INSERT INTO userurl'));
});

it('shares strict database connection setup across application paths', function () {
    $database = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/database.php');

    expect($database)->toContain('MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT')
        ->and($database)->toContain("set_charset('utf8mb4')")
        ->and($database)->toContain("SET time_zone = '+00:00'");

    foreach (['components/new.php', 'components/get.php', 'lib/auth.php'] as $path) {
        $consumer = file_get_contents(dirname(__DIR__, 2) . '/includes/' . $path);
        expect($consumer)->toContain('openDatabaseConnection()')
            ->and($consumer)->not()->toContain('new mysqli(');
    }
});

it('retries active code collisions without permanently reserving codes', function () {
    $clipCreation = file_get_contents(dirname(__DIR__, 2) . '/includes/components/new.php');

    expect($clipCreation)->toContain('INSERT INTO userurl')
        ->and($clipCreation)->toContain('getCode() !== 1062')
        ->and($clipCreation)->not()->toContain('issued_clip_codes')
        ->and($clipCreation)->not()->toContain('clip_metrics');
});

it('starts browser sessions before rendering route output', function () {
    $router = file_get_contents(dirname(__DIR__, 2) . '/router.php');
    $errorPage = file_get_contents(dirname(__DIR__, 2) . '/includes/error.php');

    expect($router)->toContain('startBrowserSession();')
        ->and(strpos($errorPage, 'startSecureSession();'))->toBeLessThan(strpos($errorPage, '<!DOCTYPE html>'));
});

it('does not derive authentication callbacks from the request host', function () {
    $auth = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/auth.php');

    expect($auth)->not()->toContain('HTTP_HOST')
        ->and($auth)->not()->toContain("'cookieDomain'")
        ->and($auth)->toContain("'cookieSecure'")
        ->and($auth)->toContain("'cookieExpires' => 0")
        ->and($auth)->toContain('AUTH0_COOKIE_SECRET')
        ->and($auth)->toContain('subject = ?');
});

it('keeps bearer clip codes out of application telemetry', function () {
    $apache = file_get_contents(dirname(__DIR__, 2) . '/apache.conf');
    $sentry = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/sentry.php');

    expect($apache)->not()->toContain('%r')
        ->and($sentry)->toContain("'max_request_body_size' => 'none'")
        ->and($sentry)->toContain("'/[clip]'");
});

it('keeps public controllers and dependency metadata outside direct HTTP access', function () {
    $accessRules = file_get_contents(dirname(__DIR__, 2) . '/.htaccess');

    expect($accessRules)->toContain('public|scripts|tests|vendor|node_modules|js|scss')
        ->and($accessRules)->toContain('REQUEST_FILENAME} -f')
        ->and($accessRules)->toContain('css/.*|img/.*|out/.*')
        ->and($accessRules)->toContain('composer\\.(?:json|lock)')
        ->and($accessRules)->toContain('RewriteRule ^ router.php [END,QSA]')
        ->and($accessRules)->toContain('RewriteCond %{HTTP:X-Forwarded-Proto} !^https$ [NC]')
        ->and($accessRules)->not()->toContain('Header set Access-Control-Allow-Origin');
});

it('does not expose repository checkout controls through the web app', function () {
    $router = file_get_contents(dirname(__DIR__, 2) . '/router.php');
    $adminBar = file_get_contents(dirname(__DIR__, 2) . '/includes/components/html/adminbar.php');
    $menu = file_get_contents(dirname(__DIR__, 2) . '/js/menu.ts');
    $functions = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/functions.php');

    expect($router)->not()->toContain('/staging/change-branch')
        ->and($adminBar)->not()->toContain('branch-select')
        ->and($menu)->not()->toContain('change-branch')
        ->and($functions)->not()->toContain('getBranches')
        ->and(file_exists(dirname(__DIR__, 2) . '/public/change-branch.php'))->toBeFalse();
});

it('leaves generic URI validation to the RFC-aware server boundary', function () {
    $indexScript = file_get_contents(dirname(__DIR__, 2) . '/js/index.ts');
    $utilities = file_get_contents(dirname(__DIR__, 2) . '/js/lib/utils.ts');
    $homepage = file_get_contents(dirname(__DIR__, 2) . '/public/index.php');

    expect($indexScript)->not()->toContain('validateForm')
        ->and($utilities)->not()->toContain('urlRegex')
        ->and($homepage)->toContain('type="text"')
        ->and($homepage)->toContain('maxlength="2048"');
});

it('delegates URI parsing and canonicalization to League URI', function () {
    $security = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/security.php');

    expect($security)->toContain('Uri::new($uri)')
        ->and($security)->not()->toContain('$userinfoPattern')
        ->and($security)->not()->toContain('$pathPattern')
        ->and($security)->not()->toContain('$queryOrFragmentPattern');
});

it('keeps shared navigation metadata from overwriting page variables', function () {
    $adminBar = file_get_contents(dirname(__DIR__, 2) . '/includes/components/html/adminbar.php');
    $menu = file_get_contents(dirname(__DIR__, 2) . '/includes/menu.php');

    expect($adminBar)->not()->toContain('$release =')
        ->and($menu)->toContain('$releaseName');
});

it('keeps the historical clip total and browser history lifetime semantics', function () {
    $aboutPage = file_get_contents(dirname(__DIR__, 2) . '/public/about.php');
    $indexScript = file_get_contents(dirname(__DIR__, 2) . '/js/index.ts');
    $resultScript = file_get_contents(dirname(__DIR__, 2) . '/js/new.ts');
    $recentClips = file_get_contents(dirname(__DIR__, 2) . '/js/lib/recentClips.ts');

    expect($aboutPage)->toContain('SELECT COALESCE(MAX(id), 0) AS clip_count FROM userurl')
        ->and($aboutPage)->toContain("getRedis('total-clip-count-v1')")
        ->and($aboutPage)->toContain('Total clips made:')
        ->and($indexScript)->toContain('from "./lib/recentClips"')
        ->and($resultScript)->toContain('from "./lib/recentClips"')
        ->and($recentClips)->toContain('2 * 24 * 60 * 60 * 1000');
});

it('does not load infrastructure root credentials into the application environment', function () {
    $sampleEnvironment = file_get_contents(dirname(__DIR__, 2) . '/.env.sample');
    $initialization = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/init.php');

    expect($sampleEnvironment)->not()->toContain('MYSQL_ROOT_PASSWORD')
        ->and($initialization)->toContain('->allowList($appEnvironmentKeys)');
});

it('supports anonymous presigning while pinning the upload destination', function () {
    $fileApi = file_get_contents(dirname(__DIR__, 2) . '/public/api/file.php');
    $initialization = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/init.php');
    $headers = file_get_contents(dirname(__DIR__, 2) . '/includes/lib/headers.php');
    $menu = file_get_contents(dirname(__DIR__, 2) . '/includes/menu.php');

    expect($fileApi)->toContain("if (\$filesApiToken !== '')")
        ->and($fileApi)->toContain("\$upstreamHeaders[] = 'Authorization: Bearer ' . \$filesApiToken")
        ->and($fileApi)->toContain('hash_equals($allowedUploadHost, $uploadHost)')
        ->and($initialization)->toContain("\$filesApiToken !== ''")
        ->and($headers)->toContain("\$connectSources[] = 'https://' . \$filesUploadHost")
        ->and($menu)->not()->toContain('FILES_API_TOKEN')
        ->and($fileApi)->not()->toContain("str_ends_with(\$allowedUploadHost, '.amazonaws.com')");
});

it('keeps application scripts out of Cloudflare Rocket Loader', function () {
    $scriptFiles = [
        'includes/menu.php',
        'public/admin.php',
        'public/core/get.php',
        'public/core/set.php',
        'public/file.php',
        'public/index.php',
        'public/receive.php',
    ];

    foreach ($scriptFiles as $scriptFile) {
        $contents = file_get_contents(dirname(__DIR__, 2) . '/' . $scriptFile);
        preg_match_all('/<script\\b[^>]*>/i', $contents, $scriptTags);

        expect($scriptTags[0])->not()->toBeEmpty();
        foreach ($scriptTags[0] as $scriptTag) {
            expect($scriptTag)->toContain('data-cfasync="false"');
            if (str_contains($scriptTag, ' src=')) {
                expect(strpos($scriptTag, 'data-cfasync="false"'))->toBeLessThan(strpos($scriptTag, ' src='));
            }
        }
    }
});
