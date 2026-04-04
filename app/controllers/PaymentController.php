<?php
namespace App\Controllers;

class PaymentController
{
    public function verify($pdo, int $paymentId, string $notes = ''): void
    {
        $stmt = $pdo->prepare('SELECT * FROM payment_confirmations WHERE id = ? LIMIT 1');
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch();

        if (!$payment) {
            return;
        }

        $pdo->prepare("UPDATE payment_confirmations SET verification_status='verified', admin_notes=? WHERE id=?")
            ->execute([$notes, $paymentId]);

        $pdo->prepare("UPDATE orders SET payment_status='paid', order_status='processed' WHERE id=?")
            ->execute([$payment['order_id']]);
    }

    public function reject($pdo, int $paymentId, string $notes = ''): void
    {
        $stmt = $pdo->prepare('SELECT * FROM payment_confirmations WHERE id = ? LIMIT 1');
        $stmt->execute([$paymentId]);
        $payment = $stmt->fetch();

        if (!$payment) {
            return;
        }

        $pdo->prepare("UPDATE payment_confirmations SET verification_status='rejected', admin_notes=? WHERE id=?")
            ->execute([$notes, $paymentId]);

        $pdo->prepare("UPDATE orders SET payment_status='rejected' WHERE id=?")
            ->execute([$payment['order_id']]);
    }
}