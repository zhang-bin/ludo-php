<?php
return array(
    'default' => 'mysql',

    'connections' => [
        'mysql' => [
            'driver'    => 'mysql',
            'read' => array(
                'host'      => 'localhost',
                'database'  => 'ludo-php',
                'username'  => 'root',
                'password'  => '64297881',
            ),
            'write' => array(
                'host'      => 'localhost',
                'database'  => 'ludo-php',
                'username'  => 'root',
                'password'  => '64297881',
            ),
            'charset'   => 'utf8',
            'collation' => 'utf8_general_ci',
            'prefix'    => '',
        ],
        'redis' => [
            'host' => 'localhost',
            'port' => 6379
        ]
    ]
);