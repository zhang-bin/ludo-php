<?php
return [
    'permission' => [
        'name' => '系统配置',
        'icon' => 'fa-user-circle',
        'children' => [
            [
                'name' => '角色管理',
                'href' => 'permission/index',
                'icon' => 'fa-users',
                'active' => ['permission/index', 'permission/addRole', 'permission/modifyRole' ,'permission/viewRole']
            ],
            [
                'name' => '用户管理',
                'href' => 'permission/user',
                'icon' => 'fa-user',
                'active' => ['permission/user', 'permission/addUser', 'permission/modifyUser', 'permission/viewUser'],
            ],
        ]
    ],
];