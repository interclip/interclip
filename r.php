<?php
include("db.php");

function reDir($url) {
    header("Location: " . $url . "");
    die();
}


if (isset($_GET['c'])) {
    $user_code = $_GET['c'];
    $conn = new mysqli($servername, $username, $password, $DBName);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sqlquery = "SELECT * FROM userurl WHERE usr = '$user_code'";
    $result = $conn->query($sqlquery);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $url = $row['url'];
            echo "Redirecting you to " . $url;
            reDir($url);
            break;

        }
        $conn->query($sqlquery);
    } else {
        echo "URL not found";
    }

    $conn->close();
} else {
    reDir("./");
}
