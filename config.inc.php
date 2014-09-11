<?php
define('DB_HOST_M',			'localhost');
define('DB_HOST_S',			'localhost');
define('DB_NAME',			'lenovo');
define('DB_USER',			'root');
define('DB_PASSWORD',		'64297881');

define('DB_PORT',			'3306');
define('DB_TYPE',			'mysql');
define('DB_CONNECT',		0); //0: connect; 1: pconnect
define('TABLE_PREFIX',		'');

define('SITE_ROOT',			dirname(__FILE__)); //no trailing slash
define('SITE_URL',			'http://'.$_SERVER['SERVER_NAME'].'/MIDH'); //no trailing slash. You can leave blank if your application will stay in the Document Root of your Web Server

define('SITE_COPYRIGHT',	'');
define('THEME',				'');
define('DEFAULT_LANGUAGE', 	'zh-cn');
define('TIME_FORMAT', 		'Y-m-d H:i:s');
define('DATE_FORMAT', 		'Y-m-d');
define('DEFAULT_TIME_ZONE', 'Asia/Shanghai');
define('USING_MOD_REWRITE', false);

define('php', 				'.php'); //@TODO this feature is still alpha. change this to your own php extension
define('tpl', 				'.php'); //change this to your own php template extension
define('PROGRAM_CHARSET', 	'UTF-8'); //the charset of current application, this could influence the database query(set names)
define('MEMCACHE_ENABLE',   false);
define('MEMCACHE_SERVER', 	'localhost:11211'); //server list, comma seperated. eg. 127.0.0.1:11211,127.0.0.1:11212,pt.com


define('POWERED_BY',		'LudoPHP-1.0');

define('DEBUG',				true);

/*-----[Program Specified]-----*/
define('ADM', 				'admin'); //key for User login info in $_SESSION. $_SESSION[USER] = array(id=>'user id', 'uname'=>'user name');
define('USER', 				'user'); //key for User login info in $_SESSION. $_SESSION[USER] = array(id=>'user id', 'uname'=>'user name');
define('PROCESS', 			'process'); //key for Passport process info in $_SESSION. $_SESSION[PROCESS] = array(e.g. 'change_mail2'=>true);
define('SUCCESS',			'success'); //the result type of an action.
define('ALERT',				'alert');

define('API_SALT',			'Iclouds.is.very_G00d'); //api salt for nagios record. $Wo.yo@71#Agio$

//WSMDA stage const
define('FINAL_STAGE',			'2');
define('INTERNAL_STAGE',			'1');
define('PROV_STAGE',				'0');
