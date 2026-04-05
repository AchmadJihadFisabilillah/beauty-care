<?php
namespace App\Models;

class Payment extends BaseModel
{
    public function all(): array
    {
        return $this->db->query("\n            SELECT pc.*,\n                   o.order_code,\n                   o.total,\n                   u.name AS user_name,\n                   admin.name AS verified_by_name\n            FROM payment_confirmations pc\n            JOIN orders o ON o.id = pc.order_id\n            JOIN users u ON u.id = o.user_id\n            LEFT JOIN users admin ON admin.id = pc.verified_by\n            ORDER BY pc.id DESC\n        ")->fetchAll();
    }
}
