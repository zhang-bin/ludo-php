<?php
if (!empty($_SERVER['HTTP_HOST'])) die;//禁止从url访问

define('SYS_START_TIME', microtime(true));
define('LD_ENTRY', 'index');

$_SERVER['SERVER_NAME'] = null;
session_start();
require 'config.inc.php';
require 'header.php';

$argv = $_SERVER['argv'];

$help = <<<EOF
Usage: php manage.php Options [ARGS]
Options:
    -h, --help          print help message
    -C, --install-module    fast create module, include controller file, dao file and templates file
    -D, --uninstall-module  remove module, include  controller file, dao file and templates file

EOF;
define('NEW_LINE', "\r\n");
switch ($argv[1]) {
    case '-h':
    case '--help':
        echo $help;
        break;
    case '-C':
    case '--install-module':
        $moduleName = $argv[2];
        $moduleDescr = $argv[3];
        if (empty($moduleName)) {
            echo 'fatal: need follow argument module name.';
            break;
        }
        if (empty($moduleDescr)) {
            echo 'fatal: need follow argument module description.';
            break;
        }
        require 'console/module.php';
        $module = new Module();
        $result = $module->install($moduleName, $moduleDescr);
        if (true !== $result) {
            debug($result);
        } else {
            echo 'Success Done!';
        }
        break;
    case '-D':
    case '--uninstall-module':
        $moduleName = $argv[2];
        if (empty($moduleName)) {
            echo 'fatal: need follow argument module name.';
            break;
        }
        require 'console/module.php';
        $module = new Module();
        $result = $module->uninstall($moduleName);
        if (true !== $result) {
            debug($result);
        } else {
            echo 'Success Done!';
        }
        break;
    default:
        echo $help;
        break;
}
echo NEW_LINE;
die;
