<?php

$redis = new Redis();

//Connecting to Redis
$redis->connect('localhost', 6379);
 
if ($redis->ping()) {
        $redisCached = $redis->get("iosxd");

        if($redisCached) {
            echo $redisCached;
        } else {
            $redis->set("iosxd", "gamer");
            echo "Cache miss";
        }
}