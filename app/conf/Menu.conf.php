<?php
return array(
    'index' 	=> array('name' => '首页'),
    'permission' => array('name' => '权限', 'children' => array(
        'permission/index' => array('name' => '角色管理', 'include' => array('permission/addRole', 'permission/changeRole', 'permission/permissions')),
        'permission/user' => array('name' => '用户管理', 'include' => array('permission/addUser', 'permission/changeUser'))
    ))
);