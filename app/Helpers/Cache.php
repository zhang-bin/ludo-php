<?php

namespace App\Helpers;

use Ludo\Redis\BaseRedis;
use Ludo\Support\ServiceProvider;

class Cache
{
    /**
     * @param string $connectionName
     * @return BaseRedis
     */
    public static function redis($connectionName = null)
    {
        return ServiceProvider::getInstance()->getRedisHandler($connectionName);
    }

    /**
     * close connection
     */
    public static function close()
    {
        $manager = ServiceProvider::getInstance()->getRedisManagerHandler();
        foreach ($manager->getConnections() as $connectionName) {
            $manager->disconnect($connectionName);
        }
    }
}
