<?php
namespace App\Models;

class Payment extends BaseModel
{
    public function all(): array
    {
        return $this->db->query("
            SELECT pc.*, o.order_code, o.total, u.name AS user_name
            FROM payment_confirmations pc
            JOIN orders o ON o.id = pc.order_id
            JOIN users u ON u.id = o.user_id
            ORDER BY pc.id DESC
        ")->fetchAll();
    }
}