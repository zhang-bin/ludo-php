<?php
return array(
    'index' 	=> array('name' => '首页'),
    'news'      => array('name' => '新闻', 'children' => array(
        'news/index' => array('name' => '新闻列表'),
    )),
    'permission' => array('name' => '权限', 'children' => array(
        'permission/index' => array('name' => '角色管理', 'include' => array('permission/addRole', 'permission/changeRole', 'permission/permissions')),
        'permission/user' => array('name' => '用户管理')
    ))
);