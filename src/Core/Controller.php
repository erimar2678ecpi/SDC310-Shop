<?php
namespace App\Core;

abstract class Controller
{
    protected array $config;
    public function __construct(array $config) { $this->config = $config; }
    protected function view(string $template, array $data = []): void
    {
        if (!array_key_exists('flash', $data)) {
            $data['flash'] = Flash::consume();
        }

        $data['base_path'] = $this->config['base_path'] ?? '';
        $data['auth'] = [
            'is_admin' => $_SESSION['is_admin'] ?? false,
            'user_name' => $_SESSION['user_name'] ?? null
        ];
        $data['csrf_token'] = Csrf::token();

        View::render($template, $data);
    }
    protected function redirect(string $path): never { header("Location: {$path}"); exit; }

    protected function url(string $path = '/'): string
    {
        $base = rtrim($this->config['base_path'] ?? '', '/');
        $normalized = '/' . ltrim($path, '/');

        return $base === '' ? $normalized : $base . $normalized;
    }

    protected function guardCsrf(?string $redirectPath = '/'): void
    {
        if (!Csrf::validate($_POST['_token'] ?? null)) {
            Flash::add('error', 'Your session has expired. Please try again.');
            $this->redirect($this->url($redirectPath ?? '/'));
        }
    }
}
