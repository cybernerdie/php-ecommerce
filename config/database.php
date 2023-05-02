<?php

return [
    'default' => 'mysql',
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => appenv('DB_HOST', 'localhost'),
            'database' => appenv('DB_NAME', 'database'),
            'username' => appenv('DB_USERNAME', 'root'),
            'password' => appenv('DB_PASSWORD', 'root'),
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ],
    ],
    'migrations' => 'migrations',
];
