<?php
namespace App\Models;

class Cart extends BaseModel
{
    public function findByUser(int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM carts WHERE user_id = ? LIMIT 1');
        $stmt->execute([$userId]);
        return $stmt->fetch() ?: null;
    }

    public function getOrCreate(int $userId): array
    {
        $cart = $this->findByUser($userId);
        if ($cart) {
            return $cart;
        }

        $stmt = $this->db->prepare('INSERT INTO carts(user_id) VALUES(?)');
        $stmt->execute([$userId]);

        return [
            'id' => (int) $this->db->lastInsertId(),
            'user_id' => $userId,
        ];
    }

    public function clear(int $cartId): void
    {
        $stmt = $this->db->prepare('DELETE FROM cart_items WHERE cart_id = ?');
        $stmt->execute([$cartId]);
    }
}
