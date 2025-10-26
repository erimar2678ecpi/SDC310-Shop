<?php
namespace App\Models;

use App\Core\Database;

final class Category
{
    private Database $db;

    public function __construct(array $config)
    {
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration missing in Category model');
        }

        $this->db = new Database($config);
    }

    public function all(): array
    {
        return $this->db->query(
            'SELECT id, name FROM categories ORDER BY name'
        )->fetchAll();
    }
}
