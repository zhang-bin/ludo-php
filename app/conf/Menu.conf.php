<?php
return [
    'index' => ['name' => '首页'],
    'permission' => ['name' => '权限', 'children' => [
        'permission/index' => ['name' => '角色管理', 'include' => ['permission/addRole', 'permission/changeRole', 'permission/permissions']],
        'permission/user' => ['name' => '用户管理', 'include' => ['permission/addUser', 'permission/changeUser']]
    ]]
];