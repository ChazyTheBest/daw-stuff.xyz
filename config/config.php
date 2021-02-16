<?php

/* @var $domain */

$sub = APP_ENV === 'dev' ? 'www' : 'demo';
$secure = APP_ENV === 'dev' ? false : true;
$user = APP_ENV === 'dev' ? 'daw-stuff' : 'inmucrom';

return
[
    'lang' => 'en',
    'supported_languages' => [
        'en' => 'English',
        'es' => 'Español'
    ],
    'domain' => "$sub.$domain",
    'session' => [ 'id' => 'php_ses_id', 'lifetime' => 0, 'secure' => $secure, 'logout_time' => 3600 ],
    'pdo' =>
    [
        'dsn' => 'mysql:unix_socket=/run/mysqld/mysqld.sock;dbname=shop;charset=utf8mb4',
        'user' => $user, // no password, unix_socket authentication
        'options' =>
        [
            PDO::ATTR_ERRMODE           => PDO::ERRMODE_EXCEPTION,  // highly recommended
            PDO::ATTR_EMULATE_PREPARES  => FALSE                    // ALWAYS! ALWAYS! ALWAYS!
        ]
    ],
    'pagination' =>
    [
        'limit' => 8
    ],
    'cart' => [ 'cookie_expires' => 0 ],
    'files' =>
    [
        'images' =>
        [
            'path' => "/srv/http/$domain/www/img/",
            'max_file_size' => 10485760,
            'types' => [
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg'
            ],
            'size' =>
            [
                'max_w' => 4000,
                'min_w' => 286,
                'max_h' => 4000,
                'min_h' => 180
            ],
            'thumbnail' => [ 'width' => 100, 'height' => 65 ]
        ]
    ]
];
