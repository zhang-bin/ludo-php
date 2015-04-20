<?php
if (!empty($_SERVER['HTTP_HOST'])) die;//禁止从url访问

$_SERVER['SERVER_NAME'] = null;
define('SYS_START_TIME', microtime(true));

session_start();
require 'config.inc.php';

/**
 * @var \Ludo\Foundation\Application $app
 */
$app = require_once __DIR__.'/bootstrap/start.php';

$argv = $_SERVER['argv'];

$help = <<<EOF
Usage: php manage.php Options [ARGS]
Options:
    -h, --help          print help message
    -c, -C, --install-module    fast create module, include controller file, dao file and templates file
    -d, -D, --uninstall-module  remove module, include  controller file, dao file and templates file
    -e, -E, --exec              run script
    task-queue        run task queue daemon

EOF;
define('NEW_LINE', "\r\n");
switch ($argv[1]) {
    case '-h':
    case '--help':
        echo $help;
        break;
    case '-C':
    case '-c':
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
    case '-d':
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
    case '-E':
    case '-e':
    case '--exec':
        $app->run($argv[2]);
        break;
    case 'task-queue':
        \Ludo\Support\ServiceProvider::getInstance()->taskQueueServer($argv[2]);
        break;
    default:
        echo $help;
        break;
}
echo NEW_LINE;
die;
