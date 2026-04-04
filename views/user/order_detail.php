<?php
$title = 'Detail Pesanan';
require_login();
use App\Services\UploadService;

$id = (int) ($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, current_user_id()]);
$order = $stmt->fetch();
if (!$order) die('Order tidak ditemukan.');

$itemStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $senderName = trim($_POST['sender_name'] ?? '');
    $bankName = trim($_POST['bank_name'] ?? '');
    $amount = (float) ($_POST['transfer_amount'] ?? 0);

    try {
        $proof = UploadService::uploadImage($_FILES['proof_image'], UPLOAD_PAYMENT_DIR);
        $ins = $pdo->prepare('INSERT INTO payment_confirmations(order_id,sender_name,bank_name,transfer_amount,proof_image,verification_status) VALUES(?,?,?,?,?,?)');
        $ins->execute([$id, $senderName, $bankName, $amount, $proof, 'pending']);
        $pdo->prepare("UPDATE orders SET payment_status = 'waiting_verification' WHERE id = ?")->execute([$id]);
        flash('success', 'Bukti pembayaran berhasil diupload.');
        redirect('/user/order-detail?id=' . $id);
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
        redirect('/user/order-detail?id=' . $id);
    }
}

$bankSettings = $pdo->query("SELECT setting_key, setting_value FROM settings WHERE setting_key IN ('bank_name','bank_account_name','bank_account_number')")->fetchAll();
$bank = [];
foreach ($bankSettings as $row) {
    $bank[$row['setting_key']] = $row['setting_value'];
}

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Detail Pesanan</h1>

<div class="card">
    <p>Invoice: <strong><?= e($order['order_code']) ?></strong></p>
    <p>Total: <strong><?= rupiah($order['total']) ?></strong></p>
    <p>Status Order: <?= e($order['order_status']) ?></p>
    <p>Status Pembayaran: <?= e($order['payment_status']) ?></p>

    <h3>Transfer Bank</h3>
    <p><?= e($bank['bank_name'] ?? '') ?> - <?= e($bank['bank_account_number'] ?? '') ?> a.n <?= e($bank['bank_account_name'] ?? '') ?></p>

    <p>
        <a class="btn btn-outline" target="_blank" href="<?= whatsapp_order_url($order['order_code'], (float) $order['total']) ?>">
            Hubungi Admin via WhatsApp
        </a>
    </p>

    <hr>

    <h3>Item</h3>
    <ul>
        <?php foreach ($items as $item): ?>
            <li><?= e($item['product_name']) ?> x <?= $item['qty'] ?> = <?= rupiah($item['line_total']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="card">
    <h3>Upload Bukti Transfer</h3>
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_input() ?>
        <label>Nama Pengirim<input type="text" name="sender_name" required></label>
        <label>Bank Pengirim<input type="text" name="bank_name" required></label>
        <label>Jumlah Transfer<input type="number" name="transfer_amount" required></label>
        <label>Bukti Transfer<input type="file" name="proof_image" accept=".jpg,.jpeg,.png,.webp" required></label>
        <button class="btn">Kirim Verifikasi</button>
    </form>
</div>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>