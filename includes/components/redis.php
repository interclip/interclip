<?php

function storeRedis($key, $value)
{
    $redis = new Redis();

    //Connecting to Redis
    $redis->connect('localhost', 6379);

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

    //Connecting to Redis
    $redis->connect('localhost', 6379);

    if ($redis->ping()) {
        $redisCached = $redis->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            return false;
        }
    }
}
