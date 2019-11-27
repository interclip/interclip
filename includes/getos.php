<?php
function getOS()
{

    global $user_agent;

    $os_platform = "Unknown OS Platform";

    $os_array = array(
        '/windows nt 10/i' => 'Windows', // Windows 10
        '/windows nt 6.3/i' => 'Windows', // Windows 8.1
        '/windows nt 6.2/i' => 'Windows', // Windows 8.0
        '/windows nt 6.1/i' => 'Windows', // Windows 7
        '/windows nt 6.0/i' => 'Windows', // Windows Vista
        '/windows nt 5.2/i' => 'Windows', // Windows Server 2003/XP x64
        '/windows nt 5.1/i' => 'Windows', // Windows XP
        '/windows xp/i' => 'Windows', // Windows XP
        '/windows nt 5.0/i' => 'Windows', // Windows 2000
        '/windows me/i' => 'Windows', // Windows ME
        '/win98/i' => 'Windows', // Windows 98
        '/win95/i' => 'Windows', // Windows 95
        '/win16/i' => 'Windows', // Windows 3.11
        '/macintosh|mac os x/i' => 'Macos', // Mac OS X
        '/mac_powerpc/i' => 'Macos', // Mac OS 9
        '/linux/i' => 'Linux', // Linux
        '/ubuntu/i' => 'Ubuntu', // Ubuntu
        '/iphone/i' => 'iPhone', // iPhone
        '/ipod/i' => 'iPod', // iPod
        '/ipad/i' => 'iPad', // iPad
        '/android/i' => 'Android', // Android
        '/blackberry/i' => 'BlackBerry', // Blackberry
        '/webos/i' => 'Mobile' // Mobile
    );

    foreach ($os_array as $regex => $value)
        if (preg_match($regex, $user_agent))
            $os_platform = $value;

    return $os_platform;
}
