<?php
define('SYS_START_TIME', microtime(true));
define('LD_ENTRY', 'index');

session_start();
require 'config.inc.php';
require 'header.php';

//you can pass ctrl name to LdApplication to make that ctrl name as the index ctrl
$application = new LdApplication();
$application->run();