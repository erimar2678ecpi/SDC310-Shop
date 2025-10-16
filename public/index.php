<?php
declare(strict_types=1);

session_start();

require __DIR__ . '/../vendor/autoload.php';

$config = require __DIR__ . '/../config/config.php';

$router = new App\Core\Router($config);
$router->get('/', [App\Controllers\CatalogController::class, 'index']);
$router->get('/cart', [App\Controllers\CartController::class, 'view']);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// strip base_path if present
if (!empty($config['base_path']) && str_starts_with($uri, $config['base_path'])) {
    $uri = substr($uri, strlen($config['base_path']));
    if ($uri === '') $uri = '/';
}

$router->dispatch($method, $uri);
