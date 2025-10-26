<?php
namespace App\Core;

final class Flash
{
    public static function add(string $type, string $message): void
    {
        $_SESSION['flash'][$type][] = $message;
    }

    public static function consume(): array
    {
        $messages = $_SESSION['flash'] ?? [];
        unset($_SESSION['flash']);

        return $messages;
    }
}
