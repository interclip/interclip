<?php

include_once "./db.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $DBName);

$url = str_replace("<", "&lt;", $url);
$url = str_replace(">", "&gt;", $url);

$url = mysqli_real_escape_string($conn, $url);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function gen_uid($len = 10)
{
    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, $len);
}

$usr = gen_uid(5);
$timestamp = date("Y-m-d H:i:s");

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

    $sqlquery = "INSERT INTO userurl (id, usr, url, date) VALUES (NULL, '$usr', '$url', '$timestamp') ";
    if ($conn->query($sqlquery) === FALSE) {
        echo "Error: " . $sqlquery . "<br>" . $conn->error;
    }
}

//$conn->close();
