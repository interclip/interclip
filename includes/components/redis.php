<?php

function storeRedis($key, $value)
{
    $redis = new Redis();

    try {
        //Connecting to Redis
        $redis->connect('localhost', 6379);
    } catch (Exception $e) {
        return false; // Failed connecting to the server
    }

    if ($redis->ping()) {
        $redisCached = $redis->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            $redis->setEx($key, 604800, $value); // Expire the key in one week
            return false;
        }
    }
}

function getRedis($key)
{
    $redis = new Redis();
    
    try {
        //Connecting to Redis
        $redis->connect('localhost', 6379);
    } catch (Exception $e) {
        return false; // Failed connecting to the server
    }

    if ($redis->ping()) {
        $redisCached = $redis->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            return false;
        }
    }
}
