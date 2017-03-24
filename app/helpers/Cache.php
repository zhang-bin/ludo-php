<?php
class Cache
{
    /**
     * @var \Ludo\Redis\BaseRedis
     */
    private static $redis = array();

    /**
     * @param int $db
     * @param $host
     * @param $port
     * @return \Ludo\Redis\BaseRedis
     */
    public static function redis($db = 2, $host = null, $port = null)
    {
        if (is_null(self::$redis[$db])) {
            $redis = new \Ludo\Redis\BaseRedis();
            is_null($host) && $host = Config::get('database.connections.redis.host');
            is_null($port) && $port = Config::get('database.connections.redis.port');
            $redis->connect($host, $port);
            $redis->select($db);
            self::$redis[$db] = $redis;
        }
        return self::$redis[$db];
    }
}
