<?php
return array(
    'task_queue' => [
        'host' => '127.0.0.1',
        'bind' => '127.0.0.1',
        'port' => '9321',
        'worker_num' => 1,
        'log_file' => '/tmp/swoole-task-queue.log',
        'task_worker_num' => 1,
        'user' => 'root',
        'group' => 'root',
    ]
);