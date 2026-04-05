<?php
use App\Controllers\PaymentController;

$title = 'Payments';
require_admin();

$paymentController = new PaymentController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $paymentId = (int)($_POST['payment_id'] ?? 0);
    $action = $_POST['action'] ?? '';
    $notes = trim($_POST['admin_notes'] ?? '');

    if ($paymentId > 0) {
        if ($action === 'verify') {
            $paymentController->verify($pdo, $paymentId, (int) current_user_id(), $notes);
            admin_log('Verifikasi pembayaran', ['payment_id' => $paymentId, 'action' => 'verify']);
        }
        if ($action === 'reject') {
            $paymentController->reject($pdo, $paymentId, (int) current_user_id(), $notes);
            admin_log('Tolak pembayaran', ['payment_id' => $paymentId, 'action' => 'reject']);
        }
    }

    flash('success', 'Status pembayaran diperbarui.');
    redirect('/admin/payments');
}

$list = $pdo->query("\n    SELECT pc.*,\n           o.order_code,\n           o.total,\n           u.name AS user_name,\n           admin.name AS verified_by_name\n    FROM payment_confirmations pc\n    JOIN orders o ON o.id = pc.order_id\n    JOIN users u ON u.id = o.user_id\n    LEFT JOIN users admin ON admin.id = pc.verified_by\n    ORDER BY pc.id DESC\n")->fetchAll();
require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Payment Verification</h1>
<div class="grid">
<?php foreach ($list as $row): ?>
    <div class="card">
        <p><strong><?= e($row['order_code']) ?></strong> - <?= e($row['user_name']) ?></p>
        <p>Jumlah: <?= rupiah($row['transfer_amount']) ?></p>
        <p>Status: <?= e($row['verification_status']) ?></p>
        <?php if (!empty($row['admin_notes'])): ?>
            <p><strong>Catatan:</strong> <?= nl2br(e($row['admin_notes'])) ?></p>
        <?php endif; ?>
        <?php if (!empty($row['verified_by_name'])): ?>
            <p><strong>Diverifikasi oleh:</strong> <?= e($row['verified_by_name']) ?></p>
        <?php endif; ?>
        <?php if (!empty($row['verified_at'])): ?>
            <p><strong>Waktu verifikasi:</strong> <?= e($row['verified_at']) ?></p>
        <?php endif; ?>
        <?php if ($row['proof_image']): ?>
            <img class="proof-thumb" src="<?= BASE_URL ?>/uploads/payments/<?= e($row['proof_image']) ?>" alt="proof">
        <?php endif; ?>
        <form method="post" class="form-grid compact">
            <?= csrf_input() ?>
            <input type="hidden" name="payment_id" value="<?= $row['id'] ?>">
            <textarea name="admin_notes" placeholder="Catatan admin"><?= e($row['admin_notes'] ?? '') ?></textarea>
            <div class="inline-form">
                <button class="btn" name="action" value="verify">Verify</button>
                <button class="btn btn-danger" name="action" value="reject">Reject</button>
            </div>
        </form>
    </div>
<?php endforeach; ?>
</div>
<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>
