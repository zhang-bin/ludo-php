<?php
return [
    'system_read' => [
        'name' => '只读访问系统配置的权限',
        'url' => [
            'permission' => ['index', 'userIndex', 'viewRole', 'viewUser']
        ]
    ],
    'system_manage' => [
        'name' => '管理系统配置的权限',
        'url' => [
            'permission' => ['addRole', 'modifyRole', 'delRole', 'addUser', 'modifyUser', 'delUser']
        ]
    ],
];