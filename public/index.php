<?php

defined('APP_ENV') or define('APP_ENV', 'dev');

ini_set('display_errors', 1);

$config['path'] = __DIR__;
$config += require dirname($config['path']) . '/config/config.php';

require dirname($config['path']) . '/framework/Autoloader.php';
require dirname($config['path']) . '/framework/App.php';

(new framework\App($config))->run();
