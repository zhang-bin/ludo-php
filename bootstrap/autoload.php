<?php
require __DIR__.'/../vendor/autoload.php';
use Ludo\Support\ClassLoader;

ClassLoader::addDirectories(LD_CTRL_PATH);
ClassLoader::addDirectories(LD_DAO_PATH);
ClassLoader::addDirectories(LD_MODEL_PATH);
ClassLoader::addDirectories(LD_HELPER_PATH);

ClassLoader::register();

require_once __DIR__.'/../vendor/ludo/framework/src/Ludo/Foundation/start.php';






