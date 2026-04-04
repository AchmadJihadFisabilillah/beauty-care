<?php
namespace App\Models;

class Product extends BaseModel
{
    public function allActive(int $limit = 8): array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, b.name AS brand_name
            FROM products p
            JOIN brands b ON b.id = p.brand_id
            WHERE p.is_active = 1
            ORDER BY p.id DESC
            LIMIT ?
        ");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT p.*, b.name AS brand_name, c.name AS category_name
            FROM products p
            JOIN brands b ON b.id = p.brand_id
            JOIN categories c ON c.id = p.category_id
            WHERE p.slug = ?
            LIMIT 1
        ");
        $stmt->execute([$slug]);
        return $stmt->fetch() ?: null;
    }

    public function paginate(string $q = '', int $brand = 0, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;
        $where = ' WHERE 1=1 ';
        $params = [];

        if ($q !== '') {
            $where .= ' AND (p.name LIKE ? OR p.description LIKE ? OR b.name LIKE ? OR c.name LIKE ?) ';
            $kw = "%$q%";
            array_push($params, $kw, $kw, $kw, $kw);
        }

        if ($brand > 0) {
            $where .= ' AND p.brand_id = ? ';
            $params[] = $brand;
        }

        $countStmt = $this->db->prepare("
            SELECT COUNT(*) total
            FROM products p
            JOIN brands b ON b.id = p.brand_id
            JOIN categories c ON c.id = p.category_id
            $where
        ");
        $countStmt->execute($params);
        $total = (int) $countStmt->fetch()['total'];

        $sql = "
            SELECT p.*, b.name AS brand_name, c.name AS category_name
            FROM products p
            JOIN brands b ON b.id = p.brand_id
            JOIN categories c ON c.id = p.category_id
            $where
            ORDER BY p.id DESC
            LIMIT $limit OFFSET $offset
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'pages' => max(1, (int) ceil($total / $limit)),
        ];
    }
}