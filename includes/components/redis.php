<?php

$GLOBALS["redis"] = new Redis();

try {
    // Connecting to Redis
    $GLOBALS["redis"]->connect('localhost', 6379);
    $GLOBALS["redisAvailable"] = true;
} catch (Exception $e) {
    $GLOBALS["redisAvailable"] = false; // Failed connecting to the server
}

function storeRedis($key, $value)
{

    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            $GLOBALS["redis"]->setEx($key, 60 * 60 * 24 * 7, $value); // Expire the key in one week
            return false;
        }
    } else {
        return false;
    }
}

function getRedis($key)
{

    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
