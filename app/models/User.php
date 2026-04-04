<?php
namespace App\Models;

class User extends BaseModel
{
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?,?)'
        );
        $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['password'],
            $data['role'] ?? 'user',
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function allUsers(): array
    {
        return $this->db->query("SELECT * FROM users WHERE role = 'user' ORDER BY id DESC")->fetchAll();
    }
}