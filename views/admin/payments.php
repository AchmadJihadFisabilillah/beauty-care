<?php
$title = 'Payments';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $paymentId = (int)($_POST['payment_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = trim($_POST['admin_notes'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM payment_confirmations WHERE id = ? LIMIT 1');
    $stmt->execute([$paymentId]);
    $payment = $stmt->fetch();

    if ($payment) {
        if ($action === 'verify') {
            $pdo->prepare("UPDATE payment_confirmations SET verification_status='verified', admin_notes=? WHERE id=?")->execute([$notes, $paymentId]);
            $pdo->prepare("UPDATE orders SET payment_status='paid', order_status='processed' WHERE id=?")->execute([$payment['order_id']]);
        }
        if ($action === 'reject') {
            $pdo->prepare("UPDATE payment_confirmations SET verification_status='rejected', admin_notes=? WHERE id=?")->execute([$notes, $paymentId]);
            $pdo->prepare("UPDATE orders SET payment_status='rejected' WHERE id=?")->execute([$payment['order_id']]);
        }
    }
    flash('success', 'Status pembayaran diperbarui.');
    redirect('/admin/payments');
}

$list = $pdo->query("SELECT pc.*, o.order_code, o.total, u.name AS user_name FROM payment_confirmations pc JOIN orders o ON o.id = pc.order_id JOIN users u ON u.id = o.user_id ORDER BY pc.id DESC")->fetchAll();
require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Payment Verification</h1>
<div class="grid">
<?php foreach ($list as $row): ?>
    <div class="card">
        <p><strong><?= e($row['order_code']) ?></strong> - <?= e($row['user_name']) ?></p>
        <p>Jumlah: <?= rupiah($row['transfer_amount']) ?></p>
        <p>Status: <?= e($row['verification_status']) ?></p>
        <?php if ($row['proof_image']): ?>
            <img class="proof-thumb" src="<?= BASE_URL ?>/uploads/payments/<?= e($row['proof_image']) ?>" alt="proof">
        <?php endif; ?>
        <form method="post" class="form-grid compact">
            <?= csrf_input() ?>
            <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
            <textarea name="admin_notes" placeholder="Catatan admin"></textarea>
            <div class="inline-form">
                <button class="btn" name="action" value="verify">Verify</button>
                <button class="btn btn-danger" name="action" value="reject">Reject</button>
            </div>
        </form>
    </div>
<?php endforeach; ?>
</div>
<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>
