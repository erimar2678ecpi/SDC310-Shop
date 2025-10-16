<?php
return [
    'db' => [
        'host' => getenv('DB_HOST') ?: '127.0.0.1',
        'port' => getenv('DB_PORT') ?: 3306,
        'name' => getenv('DB_NAME') ?: 'shopdb',
        'user' => getenv('DB_USER') ?: 'root',
        'pass' => getenv('DB_PASS') ?: '',
    ],
    'base_path' => '/Week2Shop/public',
    'tax_rate' => 0.05,  // 5%
    'ship_rate' => 0.10, // 10% of pre-tax subtotal
];
