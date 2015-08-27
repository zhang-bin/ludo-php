<?php
return [
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'read' => [
                'host'      => 'localhost',
                'database'  => 'ludo-php',
                'username'  => 'root',
                'password'  => '64297881',
            ],
            'write' => [
                'host'      => 'localhost',
                'database'  => 'ludo-php',
                'username'  => 'root',
                'password'  => '64297881',
            ],
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ],
        'redis' => [
            'host' => 'localhost',
            'port' => 6379
        ]
    ],
];
