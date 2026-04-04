<?php
namespace App\Controllers;

class ReportController
{
    public function monthly($pdo): array
    {
        return $pdo->query("
            SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total_orders, COALESCE(SUM(total),0) AS total_sales
            FROM orders
            WHERE payment_status='paid'
            GROUP BY DATE_FORMAT(created_at, '%Y-%m')
            ORDER BY month DESC
        ")->fetchAll();
    }
}