<?php
if (!DEBUG) {
    error_reporting(0);
} else {
    ini_set('display_errors', '1');
    error_reporting(E_ALL ^E_NOTICE);
}
if (!get_cfg_var('short_open_tag')) {
    if (ini_get('short_open_tag')) {
        echo 'Warning: you\'d better turn on your short_open_tag in your PHP.ini for speed performance';
    } else {
        die('Pls turn on "short_open_tag" in your php.ini');
    }
}
date_default_timezone_set(DEFAULT_TIME_ZONE);

require __DIR__.'/paths.php';
require __DIR__.'/autoload.php';
require LD_HELPER_PATH.'/userUtils.php';
return new Ludo\Foundation\Application;
