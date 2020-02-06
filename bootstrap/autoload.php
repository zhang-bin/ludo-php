<?php
require __DIR__ . '/../vendor/autoload.php';

use Ludo\Support\ClassLoader;
use Ludo\Support\Facades\Config;

ClassLoader::addDirectories(SITE_ROOT);

ClassLoader::register();

require_once __DIR__ . '/../vendor/ludo/framework/src/Ludo/Foundation/start.php';

$facades = Config::get('app.facades');
if (!empty($facades)) {
    foreach ($facades as $facade) {
        if (is_string($facade)) {
            $facade = new $facade();
        }

        if (method_exists($facade, 'register')) {
            $facade->register();
        }
    }
}

