<?php
$title = 'Order Detail';
require_admin();

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT 
        o.*, 
        u.name AS user_name, 
        u.email, 
        a.recipient_name, 
        a.phone, 
        a.address_line, 
        a.city, 
        a.province, 
        a.postal_code
    FROM orders o
    JOIN users u ON u.id = o.user_id
    JOIN addresses a ON a.id = o.address_id
    WHERE o.id = ?
    LIMIT 1
");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
    die('Order tidak ditemukan.');
}

$allowedStatus = ['new', 'processed', 'shipped', 'in_transit', 'completed', 'cancelled'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $orderStatus    = trim($_POST['order_status'] ?? $order['order_status']);
    $trackingNumber = trim($_POST['tracking_number'] ?? '');
    $courier        = trim($_POST['courier'] ?? '');
    $shippingCost   = (float) ($_POST['shipping_cost'] ?? $order['shipping_cost']);

    if (!in_array($orderStatus, $allowedStatus, true)) {
        flash('error', 'Status order tidak valid.');
        redirect('/admin/order-detail?id=' . $id);
    }

    // Recalculate total based on new shipping cost
    $subtotalVal = (float) ($order['subtotal'] ?? 0);
    $newTotal    = $subtotalVal + $shippingCost;

    $pdo->prepare('
        UPDATE orders 
        SET order_status = ?, tracking_number = ?, courier = ?, shipping_cost = ?, total = ?
        WHERE id = ?
    ')->execute([$orderStatus, $trackingNumber, $courier ?: null, $shippingCost, $newTotal, $id]);

    admin_log('Update status, resi, kurir & ongkir pesanan', [
        'order_id'        => $id,
        'status'          => $orderStatus,
        'tracking_number' => $trackingNumber,
        'courier'         => $courier,
        'shipping_cost'   => $shippingCost,
    ]);

    flash('success', 'Data pesanan berhasil diperbarui.');
    redirect('/admin/order-detail?id=' . $id);
}

$itemStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();

$subtotal      = (float) ($order['subtotal'] ?? 0);
$shippingCost  = (float) ($order['shipping_cost'] ?? 0);
$total         = (float) ($order['total'] ?? 0);
$courier       = $order['courier'] ?? '';
$trackingNumber = $order['tracking_number'] ?? '';

require BASE_PATH . '/views/layouts/admin_header.php';
?>

<h1>Order Detail</h1>

<div class="card">
    <h3>Informasi Pesanan</h3>

    <p><strong>Invoice:</strong> <?= e($order['order_code']) ?></p>
    <p><strong>User:</strong> <?= e($order['user_name']) ?> (<?= e($order['email']) ?>)</p>
    <p><strong>Penerima:</strong> <?= e($order['recipient_name']) ?> - <?= e($order['phone']) ?></p>
    <p>
        <strong>Alamat:</strong>
        <?= e($order['address_line']) ?>,
        <?= e($order['city']) ?>,
        <?= e($order['province']) ?>
        <?= e($order['postal_code']) ?>
    </p>

    <hr>

    <p><strong>Subtotal:</strong> <?= rupiah($subtotal) ?></p>
    <p><strong>Kurir:</strong> <?= $courier ? e(strtoupper($courier)) : '<em style="color:#94a3b8;">Belum diisi</em>' ?></p>
    <p><strong>No Resi:</strong> <?= $trackingNumber ? e($trackingNumber) : '<em style="color:#94a3b8;">Belum diisi</em>' ?></p>
    <p><strong>Ongkir:</strong> <?= rupiah($shippingCost) ?></p>
    <p><strong>Total Bayar:</strong> <?= rupiah($total) ?></p>

    <hr>

    <p><strong>Metode Pembayaran:</strong> <?= e($order['payment_method']) ?></p>
    <p><strong>Status Pembayaran:</strong> <?= e($order['payment_status']) ?></p>
    <p><strong>Status Order:</strong> <?= e(str_replace('_', ' ', $order['order_status'])) ?></p>

    <?php if (!empty($order['received_confirmed_at'])): ?>
    <div style="
        background:#ecfdf5;
        border:1px solid #22c55e;
        padding:15px;
        border-radius:12px;
        margin-top:14px;
    ">
        <strong style="color:#16a34a;">
             User telah mengonfirmasi barang sampai.
        </strong>
        <br>
        <small>
            Dikonfirmasi pada:
            <?= date('d-m-Y H:i', strtotime($order['received_confirmed_at'])) ?>
        </small>
    </div>
<?php else: ?>
    <div style="
        background:#f8fafc;
        border:1px solid #e5e7eb;
        padding:15px;
        border-radius:12px;
        margin-top:14px;
    ">
        <strong style="color:#64748b;">
            User belum mengonfirmasi barang sampai.
        </strong>
    </div>
<?php endif; ?>

    <?php if (!empty($order['notes'])): ?>
        <p><strong>Catatan:</strong><br><?= nl2br(e($order['notes'])) ?></p>
    <?php endif; ?>
</div>

<div class="card">
    <h3>Item Pesanan</h3>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['product_name']) ?></td>
                    <td><?= (int) $item['qty'] ?></td>
                    <td><?= rupiah($item['product_price']) ?></td>
                    <td><?= rupiah($item['line_total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <h3>Update Status, Pengiriman & Resi</h3>

    <form method="post" class="form-grid">
        <?= csrf_input() ?>

        <label>Status Order
            <select name="order_status">
                <?php foreach ($allowedStatus as $s): ?>
                    <option value="<?= $s ?>" <?= $order['order_status'] === $s ? 'selected' : '' ?>>
                        <?= ucfirst(str_replace('_', ' ', $s)) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Kurir / Jasa Pengiriman
            <input
                type="text"
                name="courier"
                value="<?= e($courier) ?>"
                placeholder="Contoh: JNE, J&T, AnterAja"
            >
        </label>

        <label>Nomor Resi
            <input
                type="text"
                name="tracking_number"
                value="<?= e($trackingNumber) ?>"
                placeholder="Contoh: JNE1234567890"
            >
        </label>

        <label>Ongkos Kirim (Rp)
            <input
                type="number"
                name="shipping_cost"
                value="<?= (int) $shippingCost ?>"
                min="0"
                step="1000"
                placeholder="0"
            >
            <small style="color:#64748b;">Subtotal: <?= rupiah($subtotal) ?> — Total akan dihitung otomatis.</small>
        </label>

        <button class="btn">Update Pesanan</button>
    </form>
</div>

<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>