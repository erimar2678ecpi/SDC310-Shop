<?php
namespace App\Models;

use App\Core\Database;

final class Product
{
    private $db;

    public function __construct(array $config)
    {
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration missing in Product model');
        }
        $this->db = new Database($config);
    }

    public function getAll(): array
    {
        return $this->db->query(
            'SELECT * FROM products ORDER BY id'
        )->fetchAll();
    }

    public function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        return $this->db->query(
            "SELECT * FROM products WHERE id IN ($placeholders)",
            $ids
        )->fetchAll();
    }
}
