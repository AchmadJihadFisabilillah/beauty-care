<?php
namespace App\Models;

class Category extends BaseModel
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM categories ORDER BY id DESC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM categories WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $name): void
    {
        $stmt = $this->db->prepare('INSERT INTO categories(name) VALUES(?)');
        $stmt->execute([$name]);
    }

    public function update(int $id, string $name): void
    {
        $stmt = $this->db->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $stmt->execute([$name, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([$id]);
    }
}