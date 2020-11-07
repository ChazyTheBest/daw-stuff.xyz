<?php

ini_set('display_errors', 1);

$config['path'] = '/srv/http/daw-stuff.xyz';

require "$config[path]/framework/Autoloader.php";
require "$config[path]/framework/App.php";

$config += require "$config[path]/config/config.php";

(new framework\App($config))->run();