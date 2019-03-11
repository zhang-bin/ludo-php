<?php
return [
    'role' => [
        'name' => '角色',
        'operations' => [
            'read'	 	=> ['name' => '查看', 'url' => ['permission/index', 'permission/roleList']],
            'create' 	=> ['name' => '添加', 'url' => ['permission/addRole']],
            'update'	=> ['name' => '修改', 'url' => ['permission/changeRole']],
            'delete'	=> ['name' => '删除', 'url' => ['permission/delRole']],
        ],
    ],
    'user' => [
        'name' => '用户',
        'operations' => [
            'read'	 	=> ['name' => '查看', 'url' => ['permission/userIndex', 'permission/userList']],
            'create' 	=> ['name' => '添加', 'url' => ['permission/addUser']],
            'update'	=> ['name' => '修改', 'url' => ['permission/changeUser']],
            'delete'	=> ['name' => '删除', 'url' => ['permission/delUser']],
        ],
    ],
];