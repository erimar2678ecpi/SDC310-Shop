<?php
namespace App\Core;

final class Csrf
{
    private const SESSION_KEY = '_csrf_token';

    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }

        return $_SESSION[self::SESSION_KEY];
    }

    public static function validate(?string $token): bool
    {
        $sessionToken = $_SESSION[self::SESSION_KEY] ?? '';

        if ($token === null || $token === '' || $sessionToken === '') {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }
}
