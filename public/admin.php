<?php

include_once "includes/lib/init.php";
include_once "includes/lib/auth.php";

include_once "includes/lib/sentry.php";
include_once "includes/lib/headers.php";
header('X-Robots-Tag: noindex, nofollow, noarchive');
include_once "includes/lib/functions.php";
include_once "includes/components/redis.php";

if ($user === false) {
    header('Location: ' . ROOT . '/login/', true, 302);
    exit;
}

if (!$isStaff) {
    http_response_code(403);
}

global $isWindows;

if ($isStaff) {
    $osinfo = getOSInformation() ?? ['version' => 'Unknown'];
    $kernel = php_uname('s') . ' ' . php_uname('r') . ' ' . php_uname('m');
    $mysqlVer = $conn instanceof mysqli ? $conn->server_info : 'n/a';
    $systemLoads = sys_getloadavg();
    $systemLoad = is_array($systemLoads) && isset($systemLoads[0]) ? $systemLoads[0] : 'n/a';
    $uptime = "n/a";
    if (is_readable('/proc/uptime')) {
        $uptimeContents = file_get_contents('/proc/uptime');
        if (is_string($uptimeContents) && preg_match('/\A([0-9]+)(?:\.[0-9]+)?\s/', $uptimeContents, $matches) === 1) {
            $uptimeSeconds = (int) $matches[1];
            $uptime = floor($uptimeSeconds / 86400) . 'd ' . floor(($uptimeSeconds % 86400) / 3600) . 'h';
        }
    }
    $runtimeUser = get_current_user() ?: 'n/a';

    $sqlquery = "SELECT COUNT(*) AS clip_count FROM userurl";
    $result = $conn->query($sqlquery);
    $count = 0;

    while ($row = $result->fetch_assoc()) {
        $count = (int) $row['clip_count'];
        break;
    }

    $totalLinesQuery = $conn->prepare(
        'SELECT COALESCE(SUM(TABLE_ROWS), 0) AS total_rows '
        . 'FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = ?'
    );
    $databaseName = (string) ($_ENV['DB_NAME'] ?? '');
    $totalLinesQuery->bind_param('s', $databaseName);
    $totalLinesQuery->execute();
    $totalLines = (int) ($totalLinesQuery->get_result()->fetch_assoc()['total_rows'] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "includes/components/html/meta-tags.php"; ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Interclip</title>
    <link rel="stylesheet" href="<?php echo ROOT ?>/css/admin.css">
</head>

<body>
    <?php if ($isStaff) : ?>
        <?php
        include_once "includes/menu.php";
        ?>
    <?php endif; ?>
    <main>
        <?php if ($_ENV['ENVIRONMENT'] === 'staging') : ?>
            <section id="intro">
                <main>
                    <h1>Sorry</h1>
                    <p>The admin page is not accessible while in staging.</p>
                </main>
            </section>
        <?php elseif ($isStaff) : ?>
            <section id="intro">
                    <h1>Hi, <?php echo escapeHtml($user["name"] ?? $user["nickname"] ?? 'there') ?></h1>
                    <p>Welcome to the Interclip admin dashboard!<sup>BETA</sup></p>
                    <section>
                        <aside>
                            <img alt="A lady sitting on a table" src="<?php echo ROOT ?>/img/graphics/fbdwm0.svg" height="150" />
                            <h3>Interclip files</h3>
                            <p>Total files: <strong id="files">-</strong></p>
                            <p>Total size: <strong id="filesize">-</strong></p>
                        </aside>
                        <aside>
                            <img alt="A server cluster" src="<?php echo ROOT ?>/img/graphics/1ahd5p.svg" height="150" />
                            <h3>Server metrics</h3>
                            <p>Server load: <strong><?php echo escapeHtml((string) $systemLoad) ?></strong></p>
                            <p>Storage: <strong><?php echo (formatBytes(disk_total_space('/') - disk_free_space('/'))) . "/" . (formatBytes(disk_total_space('/'))) ?></strong></p>
                            <p>Uptime: <strong><?php echo escapeHtml($uptime) ?></strong></p>
                        </aside>
                        <aside>
                            <img alt="A dashboard" src="<?php echo ROOT ?>/img/graphics/hggz12.svg" height="150" />
                            <h3>Runtime details</h3>
                            <p>Used memory: <strong><?php echo formatBytes(memory_get_usage()) ?></strong></p>
                            <p>PHP version: <strong><?php echo phpversion(); ?></strong></p>
                            <p>MySQL version: <strong><?php echo escapeHtml($mysqlVer) ?></strong></p>
                            <p>Running as: <strong><?php echo escapeHtml($runtimeUser) ?></strong></p>
                        </aside>
                        <aside>
                            <img alt="A person using a laptop" src="<?php echo ROOT ?>/img/graphics/szaqt9.svg" height="150" />
                            <h3>Service stats</h3>
                            <p>Clips: <strong><?php echo escapeHtml((string) $count) ?></strong></p>
                            <p>Database rows: <strong><?php echo escapeHtml((string) $totalLines) ?></strong></p>
                            <p>Redis items: <strong><?php echo escapeHtml((string) getTotal()) ?></strong></p>
                        </aside>
                        <aside>
                            <img alt="A lady looking at a database symbol" src="<?php echo ROOT ?>/img/graphics/agmx1d.svg" height="150" />
                            <h3>Service status</h3>
                            <p>OS: <strong><?php echo escapeHtml(PHP_OS) ?></strong></p>
                            <p>OS version: <strong><?php echo escapeHtml((string) $osinfo['version']) ?></strong></p>
                            <p>Kernel: <strong><?php echo escapeHtml($kernel) ?></strong></p>
                        </aside>
                    </section>
            </section>
    </main>

<?php elseif ($user !== false) : ?>
    <section id="intro">
        <main>
            <h1>Yikes ¯\_(ツ)_/¯</h1>
            <p>Sorry, but you don't have the permissions to access this resource.</p>
        </main>
    </section>
<?php endif; ?>
</main>
</body>
<script src="<?php echo ROOT; ?>/out/admin.js"></script>

</html>
