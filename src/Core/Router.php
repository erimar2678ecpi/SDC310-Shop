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
            $this->renderError('errors/404', 404);
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
            $this->logError($e, $method, $path);
            $this->renderError('errors/500', 500);
        }
    }

    private function renderError(string $template, int $status): void
    {
        http_response_code($status);

        $data = [
            'title' => $status === 404 ? 'Page Not Found' : 'Something Went Wrong',
            'flash' => Flash::consume(),
            'base_path' => $this->config['base_path'] ?? '',
            'auth' => [
                'is_admin' => $_SESSION['is_admin'] ?? false,
                'user_name' => $_SESSION['user_name'] ?? null
            ],
            'csrf_token' => Csrf::token()
        ];

        View::render($template, $data);
    }

    private function logError(\Throwable $e, string $method, string $path): void
    {
        $context = [
            'method' => $method,
            'path' => $path,
            'query' => $_SERVER['QUERY_STRING'] ?? '',
            'user_id' => $_SESSION['user_id'] ?? null,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null
        ];

        error_log(sprintf(
            'Router error: %s | context=%s',
            $e->getMessage(),
            json_encode($context, JSON_THROW_ON_ERROR)
        ));

        error_log($e->getTraceAsString());
    }
}
