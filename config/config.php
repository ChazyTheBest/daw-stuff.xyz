<?php

return
[
    'domain' => 'daw-stuff.xyz',
    'session' => [ 'id' => 'php_ses_id', 'lifetime' => 0, 'secure' => FALSE, 'logout_time' => 3600 ],
    'pdo' =>
    [
        'dsn' => 'mysql:unix_socket=/run/mysqld/mysqld.sock;dbname=shop;charset=utf8mb4',
        'user' => 'daw-stuff', // no password, unix_socket authentication
        'options' =>
        [
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,  // highly recommended
            PDO::ATTR_EMULATE_PREPARES  => FALSE                    // ALWAYS! ALWAYS! ALWAYS!
        ]
    ],
];
