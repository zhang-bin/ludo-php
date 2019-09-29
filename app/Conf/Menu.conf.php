<?php
return [
    'permission' => [
        'name' => '系统配置',
        'icon' => 'fa-user-circle',
        'children' => [
            [
                'name' => '角色管理',
                'href' => 'permission/index',
                'active' => ['permission/index', 'permission/addRole', 'permission/changeRole' ,'permission/viewRole']
            ],
            [
                'name' => '用户管理',
                'href' => 'permission/user',
                'active' => ['permission/user', 'permission/addUser', 'permission/changeUser', 'permission/viewUser'],
            ],
        ]
    ],
];