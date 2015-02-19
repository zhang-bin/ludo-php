<?php
/**
 * 权限相关的资源与操作的配置文件
 * 
 * 调用方法:
 * $permConf = Load::conf('Permission');
 * echo $permConf['Host']['actions']['read']; //所有服务器查看权限相关的ctrl/action
 *
 */
return array(
    'news' => array( //Resource
        'name' => '新闻',
        'operations' => array(
            'read'	 	=> array('name' => '查看', 'url' => array('news' => array('index', 'view', 'suggest'))),
            'create' 	=> array('name' => '添加', 'url' => array('news' => array('add'))),
            'update'	=> array('name' => '修改', 'url' => array('news' => array('change'))),
            'delete'	=> array('name' => '删除', 'url' => array('news' => 'del')),
        ),
    ),
);