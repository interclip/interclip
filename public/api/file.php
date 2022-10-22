<?php

require 'includes/lib/init.php';

use Aws\S3\S3Client;

// Create a S3Client
$s3Client = new S3Client([
    'version' => 'latest',
    'endpoint' => 'https://s3.eu-central-1.wasabisys.com', 
    'region' => 'eu-central-1',
    'credentials' => [
        'key' => $_ENV['AWS_ACCESS_KEY_ID'],
        'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'],
    ],
    'use_path_style_endpoint' => true
]);

$bucket = 'iclip';
// Set some defaults for form input fields
$formInputs = ['acl' => 'public-read'];


// Get file name and content type from the request
$key = $_GET['name'];
$contentType = $_GET['type'];

// Construct an array of conditions for policy
$options = [
    ['acl' => 'public-read'],
    ['bucket' => $bucket],
    ['starts-with', '$key', $key],
    ['starts-with', '$Content-Type', $contentType],
    ['starts-with', '$Content-Disposition', 'attachment;'],
    ['starts-with', '$Content-Type', $contentType],
];

// Optional: configure expiration time string
$expires = '+2 hours';


// Create presigned POST

$postObject = new \Aws\S3\PostObjectV4(
    $s3Client,
    $bucket,
    $formInputs,
    $options,
    $expires
);

// Output the URL in JSON along with the right header
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode([
    'data' => $postObject->getFormAttributes(),
    'inputs' => $postObject->getFormInputs()
]);
