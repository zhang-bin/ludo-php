<?php
define('SITE_ROOT', __DIR__);
define('SITE_URL',			'http://'.$_SERVER['SERVER_NAME'].'/ludo-php');
define('PROGRAM_CHARSET',       'UTF-8');
define('SUCCESS',               'success');
define('ALERT',                 'alert');
define('ALERT2GO',              'alert2go');
define('STATUS',                'status');
define('GO',                    'go');
define('MSG',                   'msg');
define('URL',                   'url');

define('DEFAULT_TIME_ZONE', 'Asia/Shanghai');
define('DEBUG', true);
define('php', '.php');

define('DEFAULT_LANGUAGE', 	'zh-cn');
define('TIME_FORMAT', 		'Y-m-d H:i:s');
define('DATE_FORMAT', 		'Y-m-d');
define('USING_MOD_REWRITE', false);

define('USER', 'user');

define('PAGE_SIZE',    10);
define('PAGE_SPAN',    6);

define('API_SALT', 'Ludo-PHP');
define('PASSWORD_SALT',     'r#@k1#@55%r7*w!t^g8=&f');

define('LOGGER_LEVEL_DEBUG',		1);
define('LOGGER_LEVEL_INFO',			2);
define('LOGGER_LEVEL_WARN',			3);
define('LOGGER_LEVEL_ERROR',		4);
define('LOGGER_LEVEL_FATAL',		5);

define('LOGGER_LEVEL_DEFAULT', LOGGER_LEVEL_DEBUG);