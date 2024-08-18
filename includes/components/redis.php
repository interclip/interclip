<?php

$GLOBALS["redisAvailable"] = true;

try {
    $GLOBALS["redis"] = new Redis();
    $GLOBALS["redis"]->connect('localhost', 6379);
    $GLOBALS["redisAvailable"] = true;
} catch (Exception $e) {
    // Handle the exception or log the error
    error_log("Redis connection failed: " . $e->getMessage());
    $GLOBALS["redisAvailable"] = false;
}

function ipHit($hashedIP) {
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisKey = "ip-" . substr($hashedIP, 0, 7);

        $GLOBALS["redis"]->incr($redisKey);
        $GLOBALS["redis"]->expire($redisKey, 30);
        $count = $GLOBALS["redis"]->get($redisKey);

        return $count;
    } else {
        return 0;
    }
}

function getTotal() {
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        return $GLOBALS["redis"]->dbSize();
    } else {
        return "n/a";
    }
}

/**
 * Stores a key value pair in Redis
 *
 * @param  mixed $key
 * @param  mixed $value
 * @param  mixed $expiration (optional)
 * @return void
 */
function storeRedis($key, $value, $expiration = 60 * 60 * 24 * 7) {
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);

        if ($redisCached) {
            return $redisCached;
        } else {
            $GLOBALS["redis"]->setEx($key, $expiration, $value);
            return false;
        }
    } else {
        return false;
    }
}

function getRedis($key) {
    if ($GLOBALS["redisAvailable"] && $GLOBALS["redis"]->ping()) {
        $redisCached = $GLOBALS["redis"]->get($key);
        if ($redisCached) {
            return $redisCached;
        } else {
            return false;
        }
    } else {
        error_log("Redis not available, cannot get " . $key);
        return false;
    }
}
