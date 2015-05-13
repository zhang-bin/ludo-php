<?php
define('SYS_START_TIME', microtime(true));


session_start();
require 'config.inc.php';

if (DEBUG) {
    xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
}
/**
 * @var \Ludo\Foundation\Application $app
 */
$app = require_once __DIR__.'/bootstrap/start.php';
$app->run();
