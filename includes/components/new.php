<?php

include_once "./components/rate.php";
include_once "./verify-domain.php";

function createClip($url) {
    noteLimit("set");

    $err = "";

    // Create connection
    $conn = new mysqli($_ENV['DB_SERVER'], $_ENV['USERNAME'], $_ENV['PASSWORD'], $_ENV['DB_NAME']);
    
    $url = htmlspecialchars($url);

    $url = mysqli_real_escape_string($conn, $url);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    function gen_uid($len = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
    }

    $usr = gen_uid(5);

    /* Expiry of clips */

    $startdate = strtotime("Today");
    $expires = strtotime("+1 month", $startdate);
    $expiryDate = date("Y-m-d", $expires);

    $sqlquery = "SELECT * FROM userurl WHERE url = '$url'";
    $result = $conn->query($sqlquery);

    $duplicateCodeQuery = "SELECT * FROM `userurl` WHERE usr = '$usr'";
    $duplicateCodeResult = $conn->query($duplicateCodeQuery);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usr = $row['usr'];
            break;
        }
        $conn->query($sqlquery);
    } else {
        while ($duplicateCodeResult->num_rows > 0) {
            $usr = gen_uid(5);
            $duplicateCodeQuery = "SELECT * FROM `userurl` WHERE usr = '$usr'";
            $duplicateCodeResult = $conn->query($duplicateCodeQuery);
        }

        echo $url;
        if (verify($url)) {
            $sqlquery = "INSERT INTO userurl (id, usr, url, date, expires) VALUES (NULL, '$usr', '$url', NOW(), '$expiryDate') ";
            if ($conn->query($sqlquery) === FALSE) {
                $err = "Error: " . $sqlquery . "<br>" . $conn->error;
            }
        } else {
            $err = "Error: this domain is not accesible nor registered.";
        }
    }

    //$conn->close();
    return [$usr, $err];
}