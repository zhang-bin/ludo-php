#!/usr/bin/php
<?php
if (PHP_SAPI != 'cli') die('Pls run from command!');

swoole_set_process_name('php git-hook.php manager');
$setting = parse_ini_file(__DIR__.'/../setting.ini', true);
$setting = $setting['git hook'];
$http = new swoole_http_server($setting['bind'], $setting['port']);
$http->set([
    'worker_num' => 1,
    'task_worker_num' => 1,
    'log_file' => $setting['log_file'],
    'open_tcp_nodelay' => true,
    'user' => $setting['user'],
    'group' => $setting['group'],
    'daemonize' => $setting['daemonize']
]);

$http->on('request', function(swoole_http_request $request, swoole_http_response $response){
    $content = $request->rawContent();
    file_put_contents('/tmp/swoole-git.log', date('Y-m-d H:i:s')."\n", FILE_APPEND);
    file_put_contents('/tmp/swoole-git.log', $content."\n\n", FILE_APPEND);
    $response->end('Done!');
    global $http;
    $http->task($content);
});

$http->on('workerStart', function(swoole_http_server $server, $workerId){
    swoole_set_process_name('php git-hook.php event worker');
});

/**
 * {
 *   "before": "4e57aedeb4fb29da158c243bffd6185c5a55f6a3",
 *   "after": "cd7fdb123429eb0da6df66b7f713e89627aaf8fa",
 *   "ref": "refs/heads/uc",
 *   "user_id": 10,
 *   "user_name": "黄涛",
 *   "project_id": 16,
 *   "repository": {
 *       "name": "summer",
 *       "url": "ssh://git@git.doboyu.com:58133/doc/summer_doc.git",
 *       "description": "夏目的文档",
 *       "homepage": "http://git.doboyu.com:88/doc/summer_doc"
 *   },
 *   "commits": [
 *       {
 *           "id": "cd7fdb123429eb0da6df66b7f713e89627aaf8fa",
 *           "message": "更新主线内容调整为第5张",
 *           "timestamp": "2015-08-14T15:27:29+08:00",
 *           "url": "http://git.doboyu.com:88/doc/summer_doc/commit/cd7fdb123429eb0da6df66b7f713e89627aaf8fa",
 *           "author": {
 *               "name": "黄韬",
 *               "email": "75647950@qq.com"
 *           }
 *       }
 *   ],
 *   "total_commits_count": 1
 * }
 *
 */
$http->on('task', function(swoole_http_server $server, $taskId, $fromId, $data){
    $content = json_decode($data, true);
    //..do something
});

$http->on('finish', function(){
   echo 'task finish';
});

$http->on('start', function(swoole_http_server $server){
    swoole_set_process_name('php git-hook.php master');
    $setting = parse_ini_file(__DIR__.'/../setting.ini', true);
    $setting = $setting['git hook'];
    //记录进程文件
    $dir = $setting['pidfile'];
    if (!is_dir($dir)) mkdir($dir);
    $masterPidFile = $dir.'git-hook.master.pid';
    file_put_contents($masterPidFile, $server->master_pid);

    $managerPidFile = $dir.'git-hook.manager.pid';
    file_put_contents($managerPidFile, $server->manager_pid);
});


$http->start();