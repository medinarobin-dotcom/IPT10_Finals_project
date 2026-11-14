<?php

return [
    'name' => 'CorePHP',

    'base_url' => 'http://localhost:8000',

    'database' => [
        'host' => getenv('DB_HOST') ?: 'localhost',
        'name' => getenv('DB_NAME') ?: 'corephp',
        'username' => getenv('DB_USER') ?: 'root',
        'password' => getenv('DB_PASSWORD') ?: ''
    ]
];