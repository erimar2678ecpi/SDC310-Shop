<?php
namespace App\Core;

final class View
{
    public static function render(string $template, array $data = []): void
    {
        extract($data);
        $templatePath = __DIR__ . '/../../src/Views/' . $template . '.php';
        require __DIR__ . '/../../src/Views/layout.php';
    }
}
