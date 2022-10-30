<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include_once "includes/header.php";
    include_once "includes/components/redis.php";
    ?>
    <title>About | Interclip</title>

    <link rel="stylesheet" type="text/css" href="<?php echo ROOT ?>/css/about.css">
</head>

<body>
    <a class="skip-link" href="#maincontent">Skip to main</a>
    <?php
    include "includes/menu.php";
    include_once "includes/components/rate.php";

    noteLimit();

    exec('git describe --abbrev=0 --tags', $release);

    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

    $sqlquery = "SELECT id FROM userurl ORDER BY ID DESC LIMIT 1";
    $result = $conn->query($sqlquery);

    $count = 0;
    while ($row = $result->fetch_assoc()) {
        $count = $row['id'];
        break;
    }

    if (!function_exists('str_contains')) {
        function str_contains($haystack, $needle)
        {
            return $needle !== '' && mb_strpos($haystack, $needle) !== false;
        }
    }

    function url_get_contents($Url)
    {
        if (!function_exists('curl_init')) {
            die('CURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Brave Chrome/93.0.4577.58 Safari/537.36',
        ));
        $output = curl_exec($ch);
        curl_close($ch);
        return $output;
    }

    // Cache the contributors
    if (!getRedis("contributors")) {
        $contributorsResponse = url_get_contents("https://api.github.com/repos/interclip/interclip/contributors", false);
        $contributorsJSON = json_decode($contributorsResponse, true);

        // Get the login of every contributor
        $contributors = array();
        foreach ($contributorsJSON as $contributor) {
            // Don't push if the contributor is a bot
            if ($contributor['type'] !== "Bot" && !str_contains(strtolower($contributor['login']), "bot")) {
                array_push($contributors, $contributor['login']);
            }
        }

        // Cache the contributors
        storeRedis("contributors", json_encode($contributors), 60 * 60 * 24);
    } else {
        $contributors = json_decode(getRedis("contributors"));
    }
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
                The project is mostly written in pure PHP and JS, but there are some
                <a href="https://docs.interclip.app/legal" target="_blank" rel="noopener noreferrer">
                    libraries and designs
                </a>
                we use to make it easier upon ourselves.
            </p>
        </section>
        <section id="facts">
            <h2>
                Facts about Interclip
            </h2>
            <ul class="facts">
                <li>
                    Latest release: <?php echo $release[0]; ?>
                    <a target="_blank" rel="noopener noreferrer" href="https://github.com/interclip/interclip/releases/tag/<?php echo $release[0]; ?>">
                        (changelog)
                    </a>
                </li>
                <li>
                    Total clips made:
                    <?php
                    echo $count;
                    ?>
                </li>
            </ul>
        </section>
    </main>

    <footer class="madeBy">
        <p> made with ❤️ by &nbsp;</p>
        <div class="avatar-stack">
            <?php foreach ($contributors as $contributor) : ?>
                <a href="https://github.com/<?php echo $contributor; ?>" target="_blank" rel="noopener noreferrer">
                    <img src="https://images.weserv.nl/?url=https://github.com/<?php echo $contributor; ?>.png&w=30&output=webp" class="avatar" alt="<?php echo $contributor; ?>">
                </a>
            <?php endforeach; ?>
        </div>
        </div>

    </footer>


</body>

</html>