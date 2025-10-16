<?php
namespace App\Core;

final class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $viewsPath = dirname(__DIR__) . '/Views/';
        $templatePath = $viewsPath . $template . '.php';
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Template not found: {$template}");
        }
        require $viewsPath . 'layout.php';
    }
}
