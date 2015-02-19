<?php
define('SYS_START_TIME', microtime(true));

session_start();
require 'config.inc.php';

/**
 * @var \Ludo\Foundation\Application $app
 */
$app = require_once __DIR__.'/bootstrap/start.php';
$app->run();