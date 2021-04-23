<?php

header('Content-Type: application/json');

if (!empty($_GET['branch'])) {
    $targetBranch = $_GET['branch'];
    exec("git checkout $targetBranch");
    echo json_encode(["status" => "success", "result" => "Executed checkout to $targetBranch"]);
} else {
    echo json_encode(["status" => "error"]);
}