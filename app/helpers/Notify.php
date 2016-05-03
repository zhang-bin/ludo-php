<?php
/**
 * Class Notify
 *
 * 调用异步任务进程，发起通知
 *
 */
class Notify {
    /**
     * @var swoole_client
     */
    private static $_client = null;

    public static function init() {
        if (is_null(self::$_client)) {
            self::$_client = new swoole_client(SWOOLE_TCP, SWOOLE_SOCK_SYNC);
        }
    }

    /**
     * 执行
     *
     * @param int $cmd 命令码
     * @param array $data 数据体
     * @param bool $multi 是否需要多次发送
     * @return array
     */
    public static function run($cmd, array $data, $multi = false) {
        self::init();
        if (!$multi) $data = array($data);
        $result = array();
        $port = Config::get('server.pull.port');
        foreach ($data as $datum) {
            $response = array('data' => $datum);
            BaseCtrl::addResponseSign($response);
            $response['cmd'] = $cmd;
            $ip = self::getPlayerSocketServer($datum['playerId']);
            self::$_client->connect($ip, $port);
            \Ludo\Log\Logger::getInstance()->info(sprintf('notify response is: %s', json_encode($response, JSON_UNESCAPED_UNICODE)));
            $body = json_encode($response);
            $len = strlen($body);
            $sendData = pack('C', 134).pack('N', $len+2).pack('n', API_SOCKET_SEND).$body;
            $result[] = self::$_client->send($sendData);
            self::$_client->close();
        }
        return $result;
    }

    public static function broadcast($text, $times, $replacement = array()) {
        $client = new swoole_client(SWOOLE_TCP, SWOOLE_SOCK_SYNC);
        $servers = self::getSocketServers();
        $port = Config::get('server.pull.port');
        foreach ($servers as $server) {
            $client->connect($server, $port);
            $response = array('data' => array('text' => $text, 'times' => $times, 'replacement' => $replacement));
            BaseCtrl::addResponseSign($response);
            $body = json_encode($response);
            $len = strlen($body);
            $data = pack('C', 134).pack('N', $len+2).pack('n', API_SOCKET_BROADCAST).$body;
            $client->send($data);
            $client->close();
        }
    }

    public static function luckyMoney($playerId, $data) {
        $client = new swoole_client(SWOOLE_TCP, SWOOLE_SOCK_SYNC);
        $ip = self::getPlayerSocketServer($playerId);
        $port = Config::get('server.pull.port');
        $client->connect($ip, $port);
        $response = array('data' => $data);
        BaseCtrl::addResponseSign($response);
        $body = json_encode($response);
        $len = strlen($body);
        $data = pack('C', 134).pack('N', $len+2).pack('n', API_SOCKET_LUCKY_MONEY).$body;
        $client->send($data);
        $client->close();
    }

    /**
     * 获取当前玩家长连接的服务器ip
     *
     * @param $playerId
     * @return null
     */
    public static function getPlayerSocketServer($playerId) {
        $redis = Cache::redis(8);
        $playerFd = $redis->get('fd_'.$playerId);
        if (empty($playerFd)) return Config::get('server.pull.host');
        $playerFd = json_decode($playerFd, true);
        return $playerFd['ip'];
    }

    public static function getSocketServers() {
        $redis = Cache::redis(8);
        $keys = $redis->keys('fd_player_*');
        $ips = array();
        foreach ($keys as $key) {
            $key = explodeSafe($key, '_');
            $ips[] = end($key);
        }
        return $ips;
    }
}