<?php

// include composer autoload
require "../vendor/autoload.php";
use Iodev\Whois\Factory;

function ping($domain) {
    function checkStatus($url) {
        $agent = "Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_8; pt-pt) AppleWebKit/533.20.25 (KHTML, like Gecko) Version/5.0.4 Safari/533.20.27";
    
        // initializes curl session
        $ch = curl_init();
    
        // sets the URL to fetch
        curl_setopt($ch, CURLOPT_URL, $url);
    
        // sets the content of the User-Agent header
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
    
        // make sure you only check the header - taken from the answer above
        curl_setopt($ch, CURLOPT_NOBODY, true);
    
        // follow "Location: " redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    
        // disable output verbose information
        curl_setopt($ch, CURLOPT_VERBOSE, false);
    
        // max number of seconds to allow cURL function to execute
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    
        // execute
        curl_exec($ch);
    
        // get HTTP response code
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
        curl_close($ch);
    
        if ($httpcode >= 200 && $httpcode < 400)
            return true;
        else
            return false;
    }
    
    // Return the domain status
    //===================
    return checkStatus($domain);
}

function whois($domain) {

    $start = microtime(true);

    // Creating default configured client
    $whois = Factory::get()->createWhois();

    if (isset($domain)) {

        // Checking availability
        if ($whois->isDomainAvailable($domain)) {
            return false;
            //echo json_encode(['status' => 'success', 'result' => ['registered' => false, 'domain' => $domain, 'took' => microtime(true) - $start]]);
        } else {
            return true;
            //echo json_encode(['status' => 'success', 'result' => ['registered' => true, 'domain' => $domain, 'took' => microtime(true) - $start]]);
        }
    } else {
        return false;
        //echo json_encode(['status' => 'error', 'result' => 'You must provide a URL parameter in the request.', 'took' => microtime(true) - $start]);
    }

}

function verify($domain) {
    $ping = ping($domain);
    return $ping ? $ping : whois($domain);
}
