<?php
return [
    'systemConfig' => [
        'name' => '系统配置',
        'icon' => '&#xe614;',
        'spread' => false,
        'children' => [
            [
                'title' => '角色管理',
                'icon' => '&#xe613;',
                'href' => 'permission/index',
                'spread' => false,
            ],
            [
                'title' => '用户管理',
                'icon' => '&#xe612;',
                'href' => 'permission/userIndex',
                'spread' => false,
            ],
        ]
    ],
];