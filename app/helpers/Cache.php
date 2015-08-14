<?php
class Cache
{
    /**
     * @var Redis
     */
    private static $_redis = array();

    /**
     * @param int $db
     * @param $host
     * @param $port
     * @return Redis
     */
    public static function redis($db = 2, $host = null, $port = null)
    {
        if (is_null(self::$_redis[$db])) {
            $redis = new Redis();
            is_null($host) && $host = Config::get('database.connections.redis.host');
            is_null($port) && $port = Config::get('database.connections.redis.port');
            $redis->connect($host, $port);
            $redis->select($db);
            self::$_redis[$db] = $redis;
        }
        return self::$_redis[$db];
    }

    /**
     *
     * @param $key
     * @param $hasKey
     * @param $value
     * @param $expire
     */
    public static function hSet($key, $hasKey, $value, $expire)
    {
        $redis = self::redis();
        if ($redis->exists($key)) {
            $redis->hSet($key, $hasKey, $value);
        } else {
            $redis->hSet($key, $hasKey, $value);
            !empty($expire) && $redis->expireAt($key, $expire);
        }
    }
}
