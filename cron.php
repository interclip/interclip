#!/usr/bin/env php
<?php

include_once "./includes/db.php";

// Create connection
$conn = new mysqli($servername, $username, $password, $DBName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlquery = "DELETE FROM `hits` where `date` < (CURRENT_TIMESTAMP - 18000)";
$result = $conn->query($sqlquery);
