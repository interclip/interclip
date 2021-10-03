<?php

include_once "includes/components/rate.php";
include_once "includes/verify-domain.php";
include_once "includes/components/redis.php";

/**
 * Checks if a given `$url` is hosted on IPFS
 * 
 * @param string $url
 * @return bool
 */
function checkIPFS($url)
{
    $headers = get_headers($url);
    $ipfs = false;

    $protocol = parse_url($url, PHP_URL_SCHEME);

    if ($protocol !== "ipfs") {
        foreach ($headers as $key => $value) {
            if (is_array($value)) {
                $value = end($value);
            }
            $headerParts = explode(":", $value);
            if ($headerParts[0] === "X-Ipfs-Path") {
                $ipfs = true;
                break;
            }
        }
    } else {
        $ipfs = true;
    }
    return $ipfs;
}

/**
 * Creates a random alpha-numberic ID.
 *
 * @param  int $len
 * @return string
 */
function gen_uid($len = 10)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
}

/**
 * Creates a new clip in the database
 *
 * @param  mixed $url
 * @return void
 */
function createClip($url)
{
    noteLimit();

    $err = "";

    // Create connection
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);

    $url = htmlspecialchars($url);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $usr = gen_uid(5);

    /* Expiry of clips */

    $startdate = strtotime("Today");
    $expires = strtotime("+1 month", $startdate);
    $expiryDate = date("Y-m-d", $expires);

    $stmt = $conn->prepare('SELECT * FROM userurl WHERE url = ?');

    $stmt->bind_param('s', $url);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usr = $row['usr'];
            break;
        }
        $ipfs = checkIPFS($url);
    } else {
        $stmt = $conn->prepare('SELECT * FROM userurl WHERE usr = ?');

        $stmt->bind_param('s', $usr);
        $stmt->execute();
        $stmt->store_result();

        while ($stmt->num_rows > 0) {
            $usr = gen_uid(5);

            $stmt->bind_param('s', $usr);
            $stmt->execute();
            $stmt->store_result();
        }

        $protocol = parse_url($url, PHP_URL_SCHEME);
        $allowed_protocols = ["https", "http", "ipfs"];

        if (!in_array($protocol, $allowed_protocols)) {
            $err = "Error: URL protocol not allowed";
        } else if ($protocol === "ipfs" || verify($url)) {
            $ipfs = checkIPFS($url);
            $stmt = $conn->prepare('INSERT INTO userurl (usr, url, date, expires) VALUES (?, ?, NOW(), ?)');

            $stmt->bind_param('sss', $usr, $url, $expiryDate);
            storeRedis($usr, $url);

            if ($stmt->execute() === FALSE) {
                $err = "Error inserting clip: <br>" . $conn->error;
            }
        } else {
            $err = "Error: this domain is not accesible nor registered.";
        }
    }

    return [$usr, $err, $ipfs];
}
