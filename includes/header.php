<?php
    define('ROOT_DIR', realpath(__DIR__ . '/..')); // Set the root directory of where Interclip sits

    require ROOT_DIR . "/vendor/autoload.php";

    /* Load the app config from .env */

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
    $dotenv->safeLoad();

    define("ROOT", $_ENV['ROOT']);

    exec('git rev-parse --verify HEAD', $sentryOutput);
    $sentryHash = $sentryOutput[0];
    $sentryRelease = substr($sentryHash, 0, 7);

    /* Sentry */
    if (!empty($_ENV['SENTRY_URL'])) {
        \Sentry\init([
            'dsn' => $_ENV['SENTRY_URL'],
            'release' => $sentryRelease, 
            'traces_sample_rate' => floatval($_ENV['TRACES_SAMPLE_RATE'])
        ]);
    }

    /* Headers */

    header("X-Frame-Options: DENY");
    header("Cross-Origin-Opener-Policy: same-origin");

    /* Authentication */

    use Auth0\SDK\Auth0;
    if($_ENV['AUTH_TYPE'] === "account") {
        if (!empty($_SERVER['HTTP_HOST'])) {
            $redirURI = $_ENV['PROTOCOL']. "://" . $_SERVER['HTTP_HOST'] . ROOT . "/login";
            $auth0 = new Auth0([
            'domain'        => $_ENV['AUTH0_DOMAIN'],
            'client_id'     => $_ENV['AUTH0_CLIENT_ID'],
            'client_secret' => $_ENV['AUTH0_CLIENT_SECRET'],
            'redirect_uri' => $redirURI,
        ]);
        } else {
            die("
            What'r you tryna do?
            Your HTTP host is empty, I have no idea why you're here.
            Are you a robot? I'm afraid of robots, don't scare me.
            If you aren't, maybe I messed something up. 
            In that case, it should like to report the error back to me so there's nothing you have to do in particular. 
            Have a nice day. Hope to see you soon, or later. 
            IDK, existence is just such an amazing concept, isn't it? 
            You can have a family, get a dog and be happy. But you're here, reading a server error message. Wow.
            ");
        }
    } elseif($_ENV['AUTH_TYPE'] === "mock") {
        $user = ["nickname" => "Admin", "email" => "admin@example.org"];
    }
?>


<!-- Primary Meta Tags -->
<meta name="title" content="Interclip - easy peasy clipboard sharing">
<meta name="description" content="Interclip is a tool for easily sharing URLs between devices or users.">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="https://interclip.app/">
<meta property="og:title" content="Interclip - easy peasy clipboard sharing">
<meta property="og:description" content="Interclip is a tool for easily sharing URLs between devices or users.">
<meta property="og:image" content="https://interclip.app/img/header.png">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="https://interclip.app/">
<meta property="twitter:title" content="Interclip - easy peasy clipboard sharing">
<meta property="twitter:description" content="Interclip is a tool for easily sharing URLs between devices or users.">
<meta property="twitter:image" content="https://interclip.app/img/header.png">

<link rel="apple-touch-icon" sizes="180x180" href="<?php echo ROOT ?>/img/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo ROOT ?>/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo ROOT ?>/img/icons/favicon-16x16.png">
<link rel="manifest" href="<?php echo ROOT ?>/site.webmanifest">

<link rel="stylesheet" href="<?php echo ROOT ?>/css/menu.css">
<link rel="stylesheet" href="<?php echo ROOT ?>/css/dark.css" media="(prefers-color-scheme: dark)">

<!-- Preconnects -->
<link rel="preconnect" href="https://fonts.gstatic.com/">
<link rel="preconnect" href="https://cdn.jsdelivr.net/">
<link rel="preconnect" href="https://cdnjs.cloudflare.com/">

<!-- External JS libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.slim.min.js"></script>
<script type="module" src="https://cdn.skypack.dev/pin/dark-mode-toggle@v0.8.0-8sz7Rv9Ou4431j6B2ucG/mode=imports,min/optimized/dark-mode-toggle.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<!-- Meta tags -->
<meta content="text/html; charset=UTF-8; X-Content-Type-Options=nosniff" http-equiv="Content-Type" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="google-site-verification" content="-YbUutUgfmvMugp0SOLLwef8BKdDcRvSoOvlQVJx4oM" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
