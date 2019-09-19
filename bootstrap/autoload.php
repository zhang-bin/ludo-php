<?php
require __DIR__.'/../vendor/autoload.php';
use Ludo\Support\ClassLoader;

ClassLoader::addDirectories(SITE_ROOT);

ClassLoader::register();

require_once __DIR__.'/../vendor/ludo/framework/src/Ludo/Foundation/start.php';
