<?php
namespace App\Models;

class CartItem extends BaseModel
{
    public function byCart(int $cartId): array
    {
        $stmt = $this->db->prepare("\n            SELECT ci.*, p.name, p.slug, p.image, p.stock, p.is_active\n            FROM cart_items ci\n            JOIN products p ON p.id = ci.product_id\n            WHERE ci.cart_id = ?\n            ORDER BY ci.id DESC\n        ");
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    public function findOne(int $cartId, int $productId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM cart_items WHERE cart_id = ? AND product_id = ? LIMIT 1');
        $stmt->execute([$cartId, $productId]);
        return $stmt->fetch() ?: null;
    }

    public function upsert(int $cartId, array $product, int $qty): void
    {
        $existing = $this->findOne($cartId, (int) $product['id']);
        $newQty = $qty;
        if ($existing) {
            $newQty += (int) $existing['qty'];
        }

        $newQty = max(1, min($newQty, (int) $product['stock']));
        $price = (float) $product['price'];
        $lineTotal = $price * $newQty;

        if ($existing) {
            $stmt = $this->db->prepare('UPDATE cart_items SET qty = ?, price_at_added = ?, line_total = ? WHERE id = ?');
            $stmt->execute([$newQty, $price, $lineTotal, $existing['id']]);
            return;
        }

        $stmt = $this->db->prepare('INSERT INTO cart_items(cart_id, product_id, qty, price_at_added, line_total) VALUES(?,?,?,?,?)');
        $stmt->execute([$cartId, $product['id'], $newQty, $price, $lineTotal]);
    }

    public function updateQty(int $cartId, int $productId, int $qty, int $stock): void
    {
        $qty = max(1, min($qty, $stock));
        $stmt = $this->db->prepare('UPDATE cart_items SET qty = ?, line_total = price_at_added * ? WHERE cart_id = ? AND product_id = ?');
        $stmt->execute([$qty, $qty, $cartId, $productId]);
    }

    public function remove(int $cartId, int $productId): void
    {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE cart_id = ? AND product_id = ?');
        $stmt->execute([$cartId, $productId]);
    }

    public function countQty(int $cartId): int
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(qty), 0) AS total_qty FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cartId]);
        return (int) ($stmt->fetch()['total_qty'] ?? 0);
    }

    public function subtotal(int $cartId): float
    {
        $stmt = $this->db->prepare('SELECT COALESCE(SUM(line_total), 0) AS subtotal FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cartId]);
        return (float) ($stmt->fetch()['subtotal'] ?? 0);
    }
}
