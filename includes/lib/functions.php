<?php
function formatBytes($bytes, $precision = 0)
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= pow(1024, $pow);

  return round($bytes, $precision) . ' ' . $units[$pow];
}

if (!function_exists('str_starts_with')) {
  function str_starts_with($haystack, $needle)
  {
    return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
  }
}

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

function reDir($url)
{
    header("Location: " . $url . "");
    die();
}