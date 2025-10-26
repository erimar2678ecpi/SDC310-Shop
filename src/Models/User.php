<?php
namespace App\Models;

use App\Core\Database;

final class User
{
    private Database $db;

    public function __construct(array $config)
    {
        if (!isset($config['db'])) {
            throw new \RuntimeException('Database configuration missing in User model');
        }

        $this->db = new Database($config);
    }

    public function findByEmail(string $email): ?array
    {
        $result = $this->db->query(
            'SELECT id, email, password, name, is_admin FROM users WHERE email = ? LIMIT 1',
            [$email]
        )->fetch();

        return $result === false ? null : $result;
    }

    public function findById(int $id): ?array
    {
        $result = $this->db->query(
            'SELECT id, email, password, name, is_admin FROM users WHERE id = ? LIMIT 1',
            [$id]
        )->fetch();

        return $result === false ? null : $result;
    }
}
