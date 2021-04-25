<!DOCTYPE html>
<html lang="en">

<?php

include_once "../includes/lib/init.php";
include_once "../includes/lib/auth.php";

include_once "../includes/lib/sentry.php";
include_once "../includes/lib/functions.php";

if (!$isWindows) {
    $systemLoad = sys_getloadavg()[0];
    $uptime = explode(',', explode(' up ', shell_exec('uptime'))[1])[0];
} else {
    $systemLoad = "n/a";
    $uptime = "n/a";
}

?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Interclip</title>
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
</head>

<body>
    <main>
        <section id="intro">
            <header>
                <h1>Hi, <?php echo $user["name"] ? $user['name'] : $user["nickname"] ?></h1>
                <p>Welcome to the Interclip admin dashboard!<sup>BETA</sup></p>
            </header>
            <aside>
                <img alt="An image of HTML, CSS and JavaScript" src="https://files.catbox.moe/fbdwm0.svg" height="150" />
                <h3>Interclip files</h3>
                <p>Total files: <strong>56</strong></p>
                <p>Total size: <strong>5GB</strong></p>
            </aside>
            <aside>
                <img alt="An image of HTML, CSS and JavaScript" src="https://files.catbox.moe/1ahd5p.svg" height="150" />
                <h3>Server metrics</h3>
                <p>Server load: <strong><?php echo $systemLoad ?></strong></p>
                <p>Storage: <strong><?php echo (formatBytes(disk_total_space('/') - disk_free_space('/'))) . "/" . (formatBytes(disk_total_space('/'))) ?></strong></p>
                <p>Uptime: <strong><?php echo $uptime ?></strong></p>
            </aside>
            <aside>
                <img alt="An image of HTML, CSS and JavaScript" src="https://files.catbox.moe/hggz12.svg" height="150" />
                <h3>Runtime details</h3>
                <p>Used memory: <strong><?php echo formatBytes(memory_get_usage()) ?></strong></p>
                <p>PHP version: <strong><?php echo phpversion(); ?></strong></p>
            </aside>
        </section>
    </main>
</body>

</html>