<?php

// include composer autoload
require "../vendor/autoload.php";
use Iodev\Whois\Factory;

function ping($domain) {
    $agent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; pt-pt) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27";

    $ch = curl_init(); // initializes curl session
    curl_setopt($ch, CURLOPT_URL, $domain); // sets the URL to fetch
    curl_setopt($ch, CURLOPT_USERAGENT, $agent); // sets the content of the User-Agent header
    curl_setopt($ch, CURLOPT_NOBODY, true); // make sure you only check the header - taken from the answer above
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow "Location: " redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return the transfer as a string
    curl_setopt($ch, CURLOPT_VERBOSE, false); // disable output verbose information
    curl_setopt($ch, CURLOPT_TIMEOUT, 5); // max number of seconds to allow cURL function to execute
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

    // execute
    curl_exec($ch);

    // get HTTP response code
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($httpcode >= 200 && $httpcode < 402)
        return true;
    else
        return false;   
}

function whois($domain) {

    // Creating default configured client
    $whois = Factory::get()->createWhois();

    if (isset($domain)) {

        // Checking availability
        if ($whois->isDomainAvailable($domain)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }

}

function verify($domain) {
    $ping = ping($domain);
    return $ping ? $ping : whois($domain);
}
