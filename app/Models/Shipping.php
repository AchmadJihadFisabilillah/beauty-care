<?php
namespace App\Models;

class Shipping extends BaseModel
{
    public function all(): array
    {
        return $this->db->query('SELECT * FROM shipping_rates ORDER BY id DESC')->fetchAll();
    }

    public function allActive(): array
    {
        return $this->db->query('SELECT * FROM shipping_rates WHERE is_active = 1 ORDER BY city ASC')->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM shipping_rates WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $label, string $city, float $cost): void
    {
        $stmt = $this->db->prepare('INSERT INTO shipping_rates(label,city,cost,is_active) VALUES(?,?,?,1)');
        $stmt->execute([$label, $city, $cost]);
    }

    public function update(int $id, string $label, string $city, float $cost, int $isActive): void
    {
        $stmt = $this->db->prepare('UPDATE shipping_rates SET label=?, city=?, cost=?, is_active=? WHERE id=?');
        $stmt->execute([$label, $city, $cost, $isActive, $id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM shipping_rates WHERE id=?');
        $stmt->execute([$id]);
    }
}