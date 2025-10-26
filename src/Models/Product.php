<?php
namespace App\Models;

use App\Core\Database;

final class Product
{
    private Database $db;

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
            'SELECT id, category_id, name, description, price, quantity FROM products ORDER BY id'
        )->fetchAll();
    }

    public function countAll(): int
    {
        return (int) $this->db->query(
            'SELECT COUNT(*) AS total FROM products'
        )->fetchColumn();
    }

    public function getPage(int $page, int $perPage): array
    {
        $page = max($page, 1);
        $perPage = max($perPage, 1);
        $offset = ($page - 1) * $perPage;

        $sql = sprintf(
            'SELECT id, category_id, name, description, price, quantity FROM products ORDER BY id LIMIT %d OFFSET %d',
            $perPage,
            $offset
        );

        return $this->db->query($sql)->fetchAll();
    }

    public function getByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        return $this->db->query(
            "SELECT id, category_id, name, description, price, quantity FROM products WHERE id IN ($placeholders)",
            $ids
        )->fetchAll();
    }

    public function find(int $id): ?array
    {
        $result = $this->db->query(
            'SELECT id, category_id, name, description, price, quantity FROM products WHERE id = ? LIMIT 1',
            [$id]
        )->fetch();

        return $result === false ? null : $result;
    }

    public function create(array $data): int
    {
        $this->db->query(
            'INSERT INTO products (category_id, name, description, price, quantity) VALUES (?, ?, ?, ?, ?)',
            [
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['quantity']
            ]
        );

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $this->db->query(
            'UPDATE products SET category_id = ?, name = ?, description = ?, price = ?, quantity = ? WHERE id = ?',
            [
                $data['category_id'],
                $data['name'],
                $data['description'],
                $data['price'],
                $data['quantity'],
                $id
            ]
        );
    }

    public function delete(int $id): void
    {
        $this->db->query(
            'DELETE FROM products WHERE id = ?',
            [$id]
        );
    }
}
