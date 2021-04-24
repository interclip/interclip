<?php

include_once "lib/init.php";
include_once "lib/auth.php";

header('Content-Type: application/json');

if ($user !== false) {
    if (!empty($_GET['branch'])) {
        $targetBranch = $_GET['branch'];
        exec("git checkout $targetBranch");
        echo json_encode(["status" => "success", "result" => "Executed checkout to $targetBranch"]);
    } else {
        echo json_encode(["status" => "error", "result" => "You need to provide a branch name :("]);
    }
} else {
    echo json_encode(["status" => "error", "result" => "Not logged in"]);
}
