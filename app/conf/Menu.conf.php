<?php
return array(
    'index' 	=> array('name' => '首页'),
    'player'      => array('name' => '玩家', 'children' => array(
        'player/index' => array('name' => '玩家列表', 'include' => array('player/add', 'player/change', 'player/del', 'player/msg')),
        'player/msgIndex' => array('name' => '玩家短信列表', 'include' => array()),
    )),
//    'permission' => array('name' => '权限', 'children' => array(
//        'permission/index' => array('name' => '角色管理', 'include' => array('permission/addRole', 'permission/changeRole', 'permission/permissions')),
//        'permission/user' => array('name' => '用户管理')
//    ))
);