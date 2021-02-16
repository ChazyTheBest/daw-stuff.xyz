<?php

defined('APP_ENV') or define('APP_ENV', 'dev');

ini_set('display_errors', 1);

$domain = APP_ENV === 'dev' ? 'daw-stuff.xyz' : 'inmucrom.com';
$config['path'] = "/srv/protected/$domain/demo";
$config += require "$config[path]/config/config.php";

require "$config[path]/framework/Autoloader.php";
require "$config[path]/framework/App.php";

(new framework\App($config))->run();
