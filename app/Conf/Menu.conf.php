<?php
return [
    'systemConfig' => [
        'name' => '系统配置',
        'children' => [
            [
                'name' => '角色管理',
                'href' => 'permission/index',
            ],
            [
                'name' => '用户管理',
                'href' => 'permission/userIndex',
            ],
        ]
    ],
];