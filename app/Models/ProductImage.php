<?php
namespace App\Models;

class ProductImage extends BaseModel
{
    public function byProduct(int $productId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC');
        $stmt->execute([$productId]);
        return $stmt->fetchAll();
    }

    public function create(int $productId, string $image, int $isPrimary = 0): void
    {
        $stmt = $this->db->prepare('INSERT INTO product_images(product_id,image,is_primary) VALUES(?,?,?)');
        $stmt->execute([$productId, $image, $isPrimary]);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM product_images WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function delete(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM product_images WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function resetPrimary(int $productId): void
    {
        $stmt = $this->db->prepare('UPDATE product_images SET is_primary = 0 WHERE product_id = ?');
        $stmt->execute([$productId]);
    }

    public function setPrimary(int $id): void
    {
        $stmt = $this->db->prepare('UPDATE product_images SET is_primary = 1 WHERE id = ?');
        $stmt->execute([$id]);
    }
}