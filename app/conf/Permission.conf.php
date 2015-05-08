<?php
return [
    'example' => [
        'name' => '示例',
        'operations' => [
            'read'	 	=> ['name' => '查看', 'url' => ['example' => ['index', 'view', 'suggest']]],
            'create' 	=> ['name' => '添加', 'url' => ['example' => ['add']]],
            'update'	=> ['name' => '修改', 'url' => ['example' => ['change']]],
            'delete'	=> ['name' => '删除', 'url' => ['example' => 'del']],
        ],
    ],
];