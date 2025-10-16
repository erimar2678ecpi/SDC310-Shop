<?php
namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $d = $config['db'];
        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $d['host'], $d['port'], $d['name']);
        try {
            $this->pdo = new PDO($dsn, $d['user'], $d['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        } catch (PDOException $e) {
            throw $e;
        }
    }

    public function allProducts(): array
    {
        $stmt = $this->pdo->query('SELECT id, name, price FROM products');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
