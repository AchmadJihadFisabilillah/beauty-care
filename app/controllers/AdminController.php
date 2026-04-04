<?php
namespace App\Controllers;

class AdminController
{
    public function stats($pdo): array
    {
        return [
            'sales' => $pdo->query("SELECT COALESCE(SUM(total),0) total FROM orders WHERE payment_status='paid'")->fetch()['total'],
            'orders' => $pdo->query("SELECT COUNT(*) total FROM orders")->fetch()['total'],
            'products' => $pdo->query("SELECT COUNT(*) total FROM products")->fetch()['total'],
            'pendingPayments' => $pdo->query("SELECT COUNT(*) total FROM payment_confirmations WHERE verification_status='pending'")->fetch()['total'],
        ];
    }
}