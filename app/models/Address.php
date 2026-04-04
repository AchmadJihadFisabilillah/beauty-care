<?php
namespace App\Models;

class Address extends BaseModel
{
    public function byUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM addresses WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO addresses(user_id,recipient_name,phone,address_line,city,province,postal_code,notes,is_default)
            VALUES(?,?,?,?,?,?,?,?,?)
        ");
        $stmt->execute([
            $data['user_id'],
            $data['recipient_name'],
            $data['phone'],
            $data['address_line'],
            $data['city'],
            $data['province'],
            $data['postal_code'],
            $data['notes'] ?? '',
            $data['is_default'] ?? 0,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id, int $userId): void
    {
        $stmt = $this->db->prepare('DELETE FROM addresses WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
    }
}