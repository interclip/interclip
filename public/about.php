<?php

require_once ROOT_DIR . '/includes/components/redis.php';
require_once ROOT_DIR . '/includes/lib/database.php';

$release = trim((string) ($_ENV['APP_RELEASE'] ?? ''));
$count = null;
$cachedCount = getRedis('total-clip-count-v1');
if (is_string($cachedCount) && preg_match('/\A\d+\z/D', $cachedCount) === 1) {
    $count = (int) $cachedCount;
} else {
    $aboutConnection = null;
    try {
        $aboutConnection = openDatabaseConnection(2);
        $result = $aboutConnection->query(
            'SELECT COALESCE(MAX(id), 0) AS clip_count FROM userurl'
        );
        $row = $result->fetch_assoc();
        $count = isset($row['clip_count']) ? (int) $row['clip_count'] : 0;
        storeRedis('total-clip-count-v1', (string) $count, 300);
    } catch (Throwable $error) {
        error_log('About page clip count failed: ' . $error->getMessage());
    } finally {
        if ($aboutConnection instanceof mysqli) {
            $aboutConnection->close();
        }
    }
}

$contributors = [];
$cachedContributors = getRedis('contributors');
if (is_string($cachedContributors)) {
    $decodedContributors = json_decode($cachedContributors, true);
    if (is_array($decodedContributors)) {
        foreach (array_slice($decodedContributors, 0, 100) as $contributor) {
            if (is_string($contributor) && preg_match('/\A[A-Za-z0-9](?:[A-Za-z0-9-]{0,38})\z/D', $contributor) === 1) {
                $contributors[] = $contributor;
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    ?>
    <title>About | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/about.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>
    <?php
    include "includes/menu.php";
    ?>
    <main id="maincontent">
        <h1>About Interclip</h1>
        <section id="about">
            <h2>
                What is Interclip?
            </h2>
            <p>
                Interclip is a handy-dandy clipboard sharing tool to share URLs between devices and users.
                You can read on in my article
                <a href="https://docs.interclip.app/#what-is-interclip" target="_blank" rel="noopener noreferrer">
                    What even is Interclip
                </a>
                or visit
                <a href="https://docs.interclip.app/" target="_blank" rel="noopener noreferrer">
                    Interclip's docs
                </a>
                for usage guides and many other docs.
            </p>
        </section>
        <section id="libs">
            <h2>
                Interclip's code
            </h2>
            <p>
                Interclip's code is in its entirety published on
                <a href="https://github.com/interclip/interclip" target="_blank" rel="noopener noreferrer">GitHub</a>.
                The project is mostly written in PHP and Typescript with a MySQL Database powering it all.
            </p>
        </section>
        <section id="facts">
            <h2>
                Facts about Interclip
            </h2>
            <ul class="facts">
                <li>
                    Latest release: <?php echo escapeHtml($release !== '' ? $release : 'unknown'); ?>
                    <a target="_blank" rel="noopener noreferrer" href="https://github.com/interclip/interclip/releases/tag/<?php echo escapeHtml(rawurlencode($release)); ?>">
                        (changelog)
                    </a>
                </li>
                <li>
                    Total clips made:
                    <?php echo $count === null ? 'unavailable' : escapeHtml((string) $count); ?>
                </li>
            </ul>
        </section>
    </main>

    <?php if ($contributors !== []) : ?>
    <footer class="madeBy">
        <p> made with ❤️ by &nbsp;</p>
        <div class="avatar-stack">
            <?php foreach ($contributors as $contributor) : ?>
                <a href="https://github.com/<?php echo escapeHtml(rawurlencode((string) $contributor)); ?>" target="_blank" rel="noopener noreferrer">
                    <img src="https://images.weserv.nl/?url=https://github.com/<?php echo escapeHtml(rawurlencode((string) $contributor)); ?>.png&amp;w=30&amp;output=webp" class="avatar" alt="<?php echo escapeHtml($contributor); ?>">
                </a>
            <?php endforeach; ?>
        </div>
        </div>

    </footer>
    <?php endif; ?>


</body>

</html>
