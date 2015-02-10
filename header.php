<?php
/** 
+-------------------------------------------------------------------------------
| {ProgName}
| =====================================================
| Author: Libok.Zhou <libkhorse@gmail.com>
| Home  : http://libk.8800.org
| Copyright (C)2004 - 2005 LdFirm All Rights Reserved.
| License: {LdLicense}
+-------------------------------------------------------------------------------
| include all required files for application
+-------------------------------------------------------------------------------
*/
/* ---[define absolute path and url]----- */
define('LD_CONFIG_FILE',	SITE_ROOT.'/config.inc'.php);
define('LD_APP_PATH',		SITE_ROOT.'/app'.(LD_ENTRY=='index' ? '' : '/'.LD_ENTRY)); //The app path of default moudule (index) is just under app path, no need to add module name.
define('LD_INCLUDE_PATH',	SITE_ROOT.'/includes');
define('LD_INCLUDE_URL',	SITE_URL.'/includes');
define('LD_UPLOAD_PATH',	SITE_ROOT.'/uploads');
define('LD_UPLOAD_URL',		SITE_URL.'/uploads');
define('LD_UPLOAD_IMG_PATH',LD_UPLOAD_PATH.'/img');
define('LD_UPLOAD_IMG_URL', LD_UPLOAD_URL.'/img');
define('LD_UPLOAD_TMP_PATH',LD_UPLOAD_PATH.'/tmp');
define('LD_CHART_PATH',		LD_UPLOAD_PATH.'/chart');
define('LD_CHART_URL',		LD_UPLOAD_URL.'/chart');
define('LD_KERNEL_PATH',	LD_INCLUDE_PATH.'/kernel');
define('LD_UTIL_PATH',		LD_INCLUDE_PATH.'/utils');
define('LD_UTIL_URL',		LD_INCLUDE_URL.'/utils');
define('SLASH',				DIRECTORY_SEPARATOR);
define('LD_PORTAL_URL',		SITE_URL.'/'.LD_ENTRY.php);

define('LD_CTRL_PATH',		LD_APP_PATH.'/controllers');
define('LD_DAO_PATH',		LD_APP_PATH.'/daos');	
define('LD_MODEL_PATH',		LD_APP_PATH.'/models');
define('LD_LANGUAGE_PATH',	LD_APP_PATH.'/languages');
define('LD_HELPER_PATH',	LD_APP_PATH.'/helpers');
define('LD_CONF_PATH',		LD_APP_PATH.'/conf');
define('TPL_ROOT', 			LD_APP_PATH.'/templates');
define('TPL_URL', 			SITE_URL.'/app/templates');
define('THEME_ROOT',		TPL_ROOT. (THEME ? '/'.THEME : '') );
define('THEME_URL',			TPL_URL. (THEME ? '/'.THEME : '') );

/* ---[make all php configuration consistently]----- */
//timezone
if (isset($_SESSION[USER]['timezone'])) {
	date_default_timezone_set($_SESSION[USER]['timezone']);
} else {
	date_default_timezone_set(DEFAULT_TIME_ZONE);
}
if (!get_cfg_var('short_open_tag')) {
	if (ini_get('short_open_tag')) {
		echo '<span class="warning">Warning: you\'d better turn on your short_open_tag in your PHP.ini for speed performance</span>'; //turn on in .htaccess
	} else {
		die('Pls turn on "short_open_tag" in your php.ini');
	}
}
if (!DEBUG) {
	error_reporting(0);
} else {
	ini_set('display_errors', '1');
	error_reporting(E_ALL ^E_NOTICE);
}


ini_set('magic_quotes_runtime', false);
if (get_magic_quotes_gpc()) {
	function stripslashes_deep($value) {
	    return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
	$_POST = stripslashes_deep($_POST);
	$_GET = stripslashes_deep($_GET);
	$_COOKIE = stripslashes_deep($_COOKIE);
}

/* ---[define absolute path and url]----- */
require LD_KERNEL_PATH.'/LdKernel'.php;
require LD_KERNEL_PATH.'/LdLanguage'.php;
require LD_KERNEL_PATH.'/LdException'.php;
require LD_KERNEL_PATH.'/Ld'.php;
require LD_KERNEL_PATH.'/LdBaseCtrl'.php;
require LD_KERNEL_PATH.'/LdBaseDao'.php;
require LD_KERNEL_PATH.'/LdApplication'.php;
require LD_KERNEL_PATH.'/LdTemplate'.php;
require LD_KERNEL_PATH.'/LdValidator'.php;
require LD_KERNEL_PATH.'/LdFilter'.php;
require LD_KERNEL_PATH.'/LdFactory'.php;
require LD_KERNEL_PATH.'/LdKvDB'.php;

require LD_UTIL_PATH.'/utils'.php;
require LD_HELPER_PATH.'/userUtils'.php;
require LD_HELPER_PATH.'/Validator'.php;
require LD_HELPER_PATH.'/Filter'.php;
require LD_HELPER_PATH.'/Load'.php;
require LD_UTIL_PATH.'/Crypter'.php;
require SITE_ROOT.'/vendor/autoload.php';

/* ---[define global constants]----- */
define('LAST_URL', 			isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
define('CURR_URL', 			currUrl());

define('PAGE_SIZE', '10');
define('PAGE_SPAN', '6');

/* ---[auto include necessarily library]----- */
function autoLoad($className) {
    if (class_exists($className)) return false;
	if (substr($className, -3, 3) == 'Dao') {
		$filename = LD_DAO_PATH.'/'.$className.php;
	} else if (substr($className, -5, 5) == 'Model') {
		$filename = LD_MODEL_PATH.'/'.$className.php;
	} else {
		$filename = LD_CTRL_PATH.'/'.$className.php; 
	}
	if (file_exists($filename) && is_readable($filename)) {
		require $filename;
	} else {
		return false;
	}
}
spl_autoload_register('autoLoad');