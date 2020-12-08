<?php

include_once "./db.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $DBName);

$url = str_replace("<", "&lt;", $url);
$url = str_replace(">", "&gt;", $url);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function gen_uid($len = 10) {
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
}

$usr = gen_uid(5);
$timestamp = date("Y-m-d H:i:s");

/* Expiry of clips */

$startdate = strtotime("Today");
$expires = strtotime("+1 week", $startdate);
$expiryDate = date("Y-m-d", $expires);

$sqlquery = "SELECT * FROM userurl WHERE url = '$url'";
$result = $conn->query($sqlquery);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $usr = $row['usr'];
        break;
    }
    $conn->query($sqlquery);
} else {
    $sqlquery = "INSERT INTO userurl (id, usr, url, date, expires) VALUES (NULL, '$usr', '$url', '$timestamp', '$expiryDate') ";
    if ($conn->query($sqlquery) === FALSE) {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }
}

$conn->close();
