<?php

use Ludo\Server\ServerInterface;
use Ludo\Server\SwooleEvent;

return [
    'servers' => [
        'test' => [
            'mode' => SWOOLE_PROCESS,
            'sock_type' => SWOOLE_SOCK_TCP,
            'type' => ServerInterface::SERVER_TCP,
            'host' => '0.0.0.0',
            'port' => 1234,
            'settings' => [
                'worker_num' => 1,
                'daemonize' => 'yes',
            ]
        ]
    ],
    'settings' => [
        'backlog' => 128,
        'log_file' => '/dev/null',
        'log_level' => 5,
        'user' => 'root',
        'group' => 'root',
        'dispatch_mode' => 2,
        'open_tcp_nodelay' => true,
        'open_length_check' => true,
        'package_length_type'   => 'N',
        'package_length_offset' => 1,
        'package_body_offset'   => 5,
        'package_max_length'    => 2000000,
        'heartbeat_idle_time' => 600,
        'heartbeat_check_interval' => 60,
        'max_request' => 10000,
    ],
    'processes' => [

    ],
    'callbacks' => [
        SwooleEvent::ON_START => [Ludo\Server\ServerCallback::class, 'start'],
        SwooleEvent::ON_WORKER_START => [Ludo\Server\ServerCallback::class, 'workerStart'],
    ],
];