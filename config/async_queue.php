<?php
return [
    'message_queue' => Ludo\AsyncTask\MessageQueue\RedisMessageQueue::class,
    'host' => '127.0.0.1',
    'port' => 6379,
    'channel_prefix' => 'ludo',
    'timeout' => 10,
    'retry_seconds' => 10,
    'handle_timeout' => 10,
];