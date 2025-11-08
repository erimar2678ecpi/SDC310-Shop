<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');
session_start();

// Minimal PSR-4 autoloader for App\ namespace -> src/
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../src/';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) return;
    $relative = substr($class, strlen($prefix));
    $file = $base_dir . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) require $file;
});

$envFile = __DIR__ . '/../.env';
if (is_file($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        if (strpos($line, '=') === false) continue;
        [$name, $value] = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // remove surrounding quotes
        $value = preg_replace('/^([\"\'])(.*)\\1$/', '$2', $value);
        putenv("{$name}={$value}");
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

$config = require __DIR__ . '/../config/config.php';

$router = new App\Core\Router($config);
$router->get('/', [App\Controllers\CatalogController::class, 'index']);
$router->get('/cart', [App\Controllers\CartController::class, 'viewCart']);
$router->get('/cart/checkout', [App\Controllers\CartController::class, 'confirm']);
$router->post('/cart/add', [App\Controllers\CartController::class, 'add']);
$router->post('/cart/remove', [App\Controllers\CartController::class, 'remove']);
$router->post('/cart/update', [App\Controllers\CartController::class, 'update']);
$router->post('/cart/checkout', [App\Controllers\CartController::class, 'checkout']);
$router->get('/admin/login', [App\Controllers\AuthController::class, 'showLogin']);
$router->post('/admin/login', [App\Controllers\AuthController::class, 'login']);
$router->post('/admin/logout', [App\Controllers\AuthController::class, 'logout']);
$router->get('/admin/products', [App\Controllers\Admin\ProductController::class, 'index']);
$router->get('/admin/products/create', [App\Controllers\Admin\ProductController::class, 'create']);
$router->post('/admin/products', [App\Controllers\Admin\ProductController::class, 'store']);
$router->get('/admin/products/edit', [App\Controllers\Admin\ProductController::class, 'edit']);
$router->post('/admin/products/update', [App\Controllers\Admin\ProductController::class, 'update']);
$router->post('/admin/products/delete', [App\Controllers\Admin\ProductController::class, 'destroy']);

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// strip base_path if present
if (!empty($config['base_path']) && str_starts_with($uri, $config['base_path'])) {
    $uri = substr($uri, strlen($config['base_path']));
    if ($uri === '') $uri = '/';
}

$router->dispatch($method, $uri);
