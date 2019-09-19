<?php

namespace App\Helpers;

use Ludo\Redis\BaseRedis;
use Ludo\Support\ServiceProvider;

class Cache
{
    /**
     * Get redis connection
     *
     * @param string $name
     * @return BaseRedis
     */
    public static function get(string $name = null)
    {
        return ServiceProvider::getInstance()->getRedisHandler($name);
    }

    /**
     * Close redis connection
     *
     * @param string|null $name
     */
    public static function close(string $name = null)
    {
        $redis = ServiceProvider::getInstance()->getRedisHandler($name);
        $redis->close();
        ServiceProvider::getInstance()->delRedisHandler($name);
    }
}
