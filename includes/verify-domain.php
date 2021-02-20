<?php

// include composer autoload
require "../vendor/autoload.php";

function verify($domain) {

    $start = microtime(true);

    use Iodev\Whois\Factory;

    // Creating default configured client
    $whois = Factory::get()->createWhois();

    if (isset($domain)) {

        // Checking availability
        if ($whois->isDomainAvailable($domain)) {
            echo json_encode(['status' => 'success', 'result' => ['registered' => false, 'domain' => $domain, 'took' => microtime(true) - $start]]);
        } else {
            echo json_encode(['status' => 'success', 'result' => ['registered' => true, 'domain' => $domain, 'took' => microtime(true) - $start]]);
        }
    } else {
        echo json_encode(['status' => 'error', 'result' => 'You must provide a URL parameter in the request.', 'took' => microtime(true) - $start]);
    }

}