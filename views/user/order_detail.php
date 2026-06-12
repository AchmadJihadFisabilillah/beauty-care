<?php
$title = 'Detail Pesanan';
require_login();

use App\Services\UploadService;

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, current_user_id()]);
$order = $stmt->fetch();

if (!$order) {
    die('Order tidak ditemukan.');
}

$itemStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();

$method = $order['payment_method'] ?? '';

$methodText = match ($method) {
    'bank_transfer' => 'Transfer Bank',
    'ewallet' => 'E-Wallet',
    'qris' => 'QRIS',
    'cod' => 'COD',
    default => 'Metode Pembayaran'
};

$subtotal = (float) ($order['subtotal'] ?? 0);
$shippingCost = (float) ($order['shipping_cost'] ?? 0);
$total = (float) ($order['total'] ?? 0);
$courier = $order['courier'] ?? '';
$shippingService = $order['shipping_service'] ?? '';
$trackingNumber = $order['tracking_number'] ?? '';
$orderStatus = strtolower(trim($order['order_status'] ?? 'new'));
$receivedConfirmedAt = $order['received_confirmed_at'] ?? '';

$trackingUrl = '';

if ($trackingNumber && $orderStatus !== 'cancelled') {
    $trackingUrl = 'https://cekresi.com/?noresi=' . urlencode($trackingNumber);
}

$trackingSteps = [
    'new' => 'Pesanan dibuat',
    'processed' => 'Pesanan diproses',
    'shipped' => 'Pesanan dikirim',
    'in_transit' => 'Dalam perjalanan',
    'completed' => 'Pesanan selesai',
];

$statusOrderMap = array_keys($trackingSteps);
$currentStepIndex = array_search($orderStatus, $statusOrderMap, true);

if ($currentStepIndex === false) {
    $currentStepIndex = -1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    if (isset($_POST['confirm_received'])) {
        if (!in_array($orderStatus, ['shipped', 'in_transit'], true)) {
            flash('error', 'Pesanan belum bisa dikonfirmasi sampai.');
            redirect('/user/order-detail?id=' . $id);
        }

        $pdo->prepare("
            UPDATE orders
            SET 
                order_status = 'completed',
                received_confirmed_at = NOW()
            WHERE id = ? AND user_id = ?
        ")->execute([$id, current_user_id()]);

        flash('success', 'Terima kasih, pesanan telah dikonfirmasi diterima.');
        redirect('/user/order-detail?id=' . $id);
    }

    $senderName = trim($_POST['sender_name'] ?? '');
    $bankName = trim($_POST['bank_name'] ?? '');
    $amount = (float) ($_POST['transfer_amount'] ?? 0);

    try {
        $proof = UploadService::uploadImage($_FILES['proof_image'], UPLOAD_PAYMENT_DIR);

        $ins = $pdo->prepare('
            INSERT INTO payment_confirmations(
                order_id,
                sender_name,
                bank_name,
                transfer_amount,
                proof_image,
                verification_status
            ) VALUES(?,?,?,?,?,?)
        ');
        $ins->execute([$id, $senderName, $bankName, $amount, $proof, 'pending']);

        $pdo->prepare("UPDATE orders SET payment_status = 'waiting_verification' WHERE id = ?")
            ->execute([$id]);

        flash('success', 'Bukti pembayaran berhasil diupload.');
        redirect('/user/order-detail?id=' . $id);
    } catch (Throwable $e) {
        flash('error', $e->getMessage());
        redirect('/user/order-detail?id=' . $id);
    }
}

$bankSettings = $pdo->query("
    SELECT setting_key, setting_value
    FROM settings
    WHERE setting_key IN ('bank_name','bank_account_name','bank_account_number')
")->fetchAll();

$bank = [];
foreach ($bankSettings as $row) {
    $bank[$row['setting_key']] = $row['setting_value'];
}

require BASE_PATH . '/views/layouts/header.php';
?>

<h1>Detail Pesanan</h1>

<div class="card">
    <h3>Informasi Pesanan</h3>

    <p>Invoice: <strong><?= e($order['order_code']) ?></strong></p>
    <p>Status Order: <?= e(ucfirst(str_replace('_', ' ', $orderStatus))) ?></p>
    <p>Status Pembayaran: <?= e($order['payment_status']) ?></p>
    <p>Metode Pembayaran: <strong><?= e($methodText) ?></strong></p>

    <hr>

    <h3>Tracking Pengiriman</h3>

    <?php if ($orderStatus === 'cancelled'): ?>
        <p style="color:#dc2626;font-weight:600;">
            Pesanan dibatalkan. Pelacakan paket tidak tersedia.
        </p>
    <?php else: ?>
        <div style="display:grid;gap:12px;margin-top:12px;">
            <?php foreach ($trackingSteps as $statusKey => $label): ?>
                <?php
                    $stepIndex = array_search($statusKey, $statusOrderMap, true);
                    $isDone = $stepIndex !== false && $stepIndex <= $currentStepIndex;
                    $isCurrent = $statusKey === $orderStatus;
                ?>

                <div style="
                    display:flex;
                    align-items:center;
                    gap:12px;
                    padding:12px;
                    border-radius:12px;
                    background: <?= $isDone ? '#ecfdf5' : '#f8fafc' ?>;
                    border:1px solid <?= $isCurrent ? '#22c55e' : '#e5e7eb' ?>;
                ">
                    <div style="
                        width:28px;
                        height:28px;
                        border-radius:50%;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        font-weight:700;
                        background: <?= $isDone ? '#22c55e' : '#e5e7eb' ?>;
                        color: <?= $isDone ? '#ffffff' : '#64748b' ?>;
                    ">
                        <?= $isDone ? '✓' : $stepIndex + 1 ?>
                    </div>

                    <div>
                        <strong><?= e($label) ?></strong>
                        <?php if ($isCurrent): ?>
                            <br><small>Status saat ini</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (in_array($orderStatus, ['shipped', 'in_transit'], true) && empty($receivedConfirmedAt)): ?>
            <form method="post" style="margin-top:16px;">
                <?= csrf_input() ?>
                <input type="hidden" name="confirm_received" value="1">

                <button
                    type="submit"
                    class="btn"
                    onclick="return confirm('Apakah Anda yakin barang sudah diterima?')"
                >
                    Konfirmasi Barang Telah Sampai
                </button>
            </form>
        <?php endif; ?>

        <?php if (!empty($receivedConfirmedAt)): ?>
            <div style="
                background:#ecfdf5;
                border:1px solid #22c55e;
                padding:15px;
                border-radius:12px;
                margin-top:16px;
            ">
                <strong style="color:#16a34a;">
                    Anda telah mengonfirmasi bahwa barang sudah diterima.
                </strong>
                <br>
                <small>
                    Dikonfirmasi pada:
                    <?= date('d-m-Y H:i', strtotime($receivedConfirmedAt)) ?>
                </small>
            </div>
        <?php elseif ($orderStatus === 'completed'): ?>
            <div style="
                background:#eff6ff;
                border:1px solid #3b82f6;
                padding:15px;
                border-radius:12px;
                margin-top:16px;
            ">
                <strong style="color:#2563eb;">
                    ✓ Pesanan telah selesai.
                </strong>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <hr>

    <h3>Informasi Pengiriman</h3>

    <?php if ($orderStatus === 'cancelled'): ?>
        <p>No Resi: <strong>Tidak tersedia karena pesanan dibatalkan.</strong></p>
    <?php else: ?>
        <?php if (!empty($courier)): ?>
            <p>Kurir: <strong><?= e(strtoupper($courier)) ?></strong></p>
        <?php endif; ?>

        <p>No Resi:
            <strong>
                <?= $trackingNumber ? e($trackingNumber) : 'Belum tersedia, pesanan sedang diproses.' ?>
            </strong>
        </p>

        <?php if ($trackingNumber && $trackingUrl): ?>
            <p>
                <a 
                    class="btn btn-outline" 
                    href="<?= e($trackingUrl) ?>" 
                    target="_blank"
                >
                    Lacak Paket
                </a>
            </p>
        <?php endif; ?>
    <?php endif; ?>

    <p>Ongkos Kirim:
        <strong>
            <?= $shippingCost > 0 ? rupiah($shippingCost) : '<em style="color:#f59e0b;">Sedang dikonfirmasi admin</em>' ?>
        </strong>
    </p>

    <hr>

    <h3>Ringkasan Pembayaran</h3>
    <p>Subtotal: <strong><?= rupiah($subtotal) ?></strong></p>
    <p>Ongkir: <strong><?= rupiah($shippingCost) ?></strong></p>
    <p>Total Bayar: <strong><?= rupiah($total) ?></strong></p>

    <hr>

    <h3><?= e($methodText) ?></h3>

    <?php if ($method === 'bank_transfer'): ?>
        <p><?= e($bank['bank_name'] ?? '') ?> - <?= e($bank['bank_account_number'] ?? '') ?> a.n <?= e($bank['bank_account_name'] ?? '') ?></p>
    <?php elseif ($method === 'ewallet'): ?>
        <p>DANA / OVO / GoPay / ShopeePay: 081234567890 a.n Glowé Skincare</p>
    <?php elseif ($method === 'qris'): ?>
        <p>Scan QRIS untuk melakukan pembayaran.</p>
        <p>
            <img 
                src="<?= BASE_URL ?>/assets/img/qr_link_gambar.png" 
                alt="QRIS" 
                style="max-width:220px;border-radius:12px;border:1px solid #eee;padding:10px;background:#fff;"
            >
        </p>
    <?php elseif ($method === 'cod'): ?>
        <p>Pembayaran dilakukan saat pesanan diterima.</p>
    <?php endif; ?>

    <p>
        <a class="btn btn-outline" target="_blank" href="<?= whatsapp_order_url($order['order_code'], (float) $order['total']) ?>">
            Hubungi Admin via WhatsApp
        </a>
    </p>

    <hr>

    <h3>Item Pesanan</h3>
    <ul>
        <?php foreach ($items as $item): ?>
            <li>
                <?= e($item['product_name']) ?> 
                x <?= (int) $item['qty'] ?> 
                = <?= rupiah($item['line_total']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

<?php if ($method !== 'cod' && in_array($order['payment_status'], ['unpaid', 'pending', 'rejected'])): ?>
    <div class="card">
        <h3>
            <?php
            echo match ($method) {
                'bank_transfer' => 'Upload Bukti Transfer',
                'ewallet' => 'Upload Bukti Pembayaran E-Wallet',
                'qris' => 'Upload Bukti Pembayaran QRIS',
                default => 'Upload Bukti Pembayaran'
            };
            ?>
        </h3>

        <form method="post" enctype="multipart/form-data" class="form-grid">
            <?= csrf_input() ?>

            <label>Nama Pengirim
                <input type="text" name="sender_name" required>
            </label>

            <label>
                <?php if ($method === 'ewallet'): ?>
                    E-Wallet
                    <input type="text" name="bank_name" value="E-Wallet" required>
                <?php elseif ($method === 'qris'): ?>
                    Metode
                    <input type="text" name="bank_name" value="QRIS" required>
                <?php else: ?>
                    Bank Pengirim
                    <input type="text" name="bank_name" required>
                <?php endif; ?>
            </label>

            <label>Jumlah Transfer
                <input type="number" name="transfer_amount" required>
            </label>

            <label>Bukti Pembayaran
                <input type="file" name="proof_image" accept=".jpg,.jpeg,.png,.webp" required>
            </label>

            <button class="btn">Kirim Verifikasi</button>
        </form>
    </div>
<?php endif; ?>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>