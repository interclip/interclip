<?php
$GLOBALS['prodvar'] = true;
$GLOBALS['local'] = "http://localhost";
$GLOBALS['dev'] = "http://unidev.hys.cz";
$GLOBALS['uni'] = "http://uni.hys.cz";
$GLOBALS['link'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";


function isProd()
{
    if ($GLOBALS['link'] == $GLOBALS['local']) {
        return false;
    } else {
        return true;
    }
};

function whatPage()
{
    $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
    if ($link == $GLOBALS['local']) {
        return 'localhost';
    } elseif ($link == $GLOBALS['dev']) {
        return 'dev';
    } elseif ($link == $GLOBALS['uni']) {
        return 'uni';
    } else {
        return '404';
    }
};
