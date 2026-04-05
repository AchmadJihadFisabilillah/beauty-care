<?php
namespace App\Models;

class Invoice extends BaseModel
{
    public function findByOrder(int $orderId): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM invoices WHERE order_id = ? LIMIT 1');
        $stmt->execute([$orderId]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $orderId, string $invoiceNumber, float $totalAmount, ?string $pdfFile = null): int
    {
        $stmt = $this->db->prepare('INSERT INTO invoices(order_id, invoice_number, invoice_date, total_amount, pdf_file) VALUES(?, ?, NOW(), ?, ?)');
        $stmt->execute([$orderId, $invoiceNumber, $totalAmount, $pdfFile]);
        return (int) $this->db->lastInsertId();
    }
}
