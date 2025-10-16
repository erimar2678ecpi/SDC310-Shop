<?php
namespace App\Core;

final class Router
{
    private array $routes = ['GET' => [], 'POST' => []];
    public function __construct(private array $config) {}
    public function get(string $path, callable|array $handler): void { $this->routes['GET'][$path] = $handler; }
    public function post(string $path, callable|array $handler): void { $this->routes['POST'][$path] = $handler; }
    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $handler = $this->routes[$method][$path] ?? null;
        
        if (!$handler) { 
            http_response_code(404); 
            echo 'Not Found'; 
            return; 
        }

        try {
            if (is_array($handler)) {
                [$class, $methodName] = $handler;
                if (!isset($this->config['db'])) {
                    throw new \RuntimeException('Database configuration is missing in router');
                }
                $controller = new $class($this->config);
                $controller->$methodName();
            } else { 
                $handler(); 
            }
        } catch (\Throwable $e) {
            error_log('Router error: ' . $e->getMessage());
            http_response_code(500);
            echo 'Internal Server Error';
        }
    }
}
