<?php
namespace App\Models;

class Order extends BaseModel
{
    public function byUser(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch() ?: null;
    }

    public function detail(int $id): ?array
    {
        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS user_name, u.email, a.recipient_name, a.phone, a.address_line, a.city, a.province, a.postal_code
            FROM orders o
            JOIN users u ON u.id = o.user_id
            JOIN addresses a ON a.id = o.address_id
            WHERE o.id = ?
            LIMIT 1
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function paginate(string $status = '', int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $where = $status ? ' WHERE o.order_status = ? ' : '';
        $params = $status ? [$status] : [];

        $countStmt = $this->db->prepare("SELECT COUNT(*) total FROM orders o $where");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $stmt = $this->db->prepare("
            SELECT o.*, u.name AS user_name
            FROM orders o
            JOIN users u ON u.id = o.user_id
            $where
            ORDER BY o.id DESC
            LIMIT $limit OFFSET $offset
        ");
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => max(1, (int) ceil($total / $limit)),
        ];
    }
}