<?php
namespace App\Core;

use PDO;
use PDOException;

final class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration is missing');
        }

        $d = $config['db'];
        $required = ['host', 'port', 'name', 'user', 'pass'];
        foreach ($required as $field) {
            if (!isset($d[$field])) {
                throw new \RuntimeException("Database configuration missing: {$field}");
            }
        }

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', 
            $d['host'],
            $d['port'],
            $d['name']
        );

        try {
            $this->pdo = new PDO(
                $dsn,
                $d['user'],
                $d['pass'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
        } catch (PDOException $e) {
            error_log("Database connection error: " . $e->getMessage());
            throw new \RuntimeException('Could not connect to database. Check your configuration.');
        }
    }

    public function query(string $sql, array $params = []): \PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            throw new \RuntimeException('Database error occurred');
        }
    }

    public function allProducts(): array
    {
        return $this->query('SELECT id, name, price, description, category_id FROM products')
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
