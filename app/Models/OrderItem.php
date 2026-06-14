<?php
namespace App\Models;

class OrderItem extends BaseModel
{
    public function byOrder(int $orderId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM order_items WHERE order_id = ?');
        $stmt->execute([$orderId]);
        return $stmt->fetchAll();
    }
}