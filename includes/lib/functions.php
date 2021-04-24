<?php

/**
 * Formats a value in bytes to its appropriate prefix
 *
 * @param  mixed $bytes
 * @return string
 */
function formatBytes($bytes)
{
    if ($bytes > 0) {
        $i = floor(log($bytes) / log(1024));
        $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        return sprintf('%.02F', round($bytes / pow(1024, $i), 1)) * 1 . ' ' . @$sizes[$i];
    } else {
        return '0 B';
    }
}

/* str_starts_with pollyfill */
if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle)
    {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

/**
 * Gets all the local git branches and returns an arrray of them.
 *
 * @return array
 */
function getBranches()
{
    exec("git branch", $gitOutput);

    $branches = [
        "all" => [],
        "current" => ''
    ];

    foreach ($gitOutput as $branchString) {
        if (str_starts_with($branchString, "*")) {
            $current = substr($branchString, 2);
            $branches["current"] = $current;
        } else {
            $currentBranchClean = str_replace("remotes/", "", $branchString);
            array_push($branches["all"], $currentBranchClean);
        }
    }
    return $branches;
}

/**
 * Redirects the user to a specified URL
 *
 * @param mixed $url
 * @return void
 */
function reDir($url)
{
    header("Location: " . $url . "");
    die();
}
