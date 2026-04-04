<?php
$title = 'Invoice';
require_login();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT o.*, u.name AS customer_name, a.recipient_name, a.phone, a.address_line, a.city, a.province, a.postal_code
    FROM orders o
    JOIN users u ON u.id = o.user_id
    JOIN addresses a ON a.id = o.address_id
    WHERE o.id = ? AND o.user_id = ? LIMIT 1");
$stmt->execute([$id, current_user_id()]);
$order = $stmt->fetch();
if (!$order) die('Invoice tidak ditemukan.');

$itemStmt = $pdo->prepare('SELECT * FROM order_items WHERE order_id = ?');
$itemStmt->execute([$id]);
$items = $itemStmt->fetchAll();

require BASE_PATH . '/views/layouts/header.php';
?>
<div class="invoice card print-area">
    <div class="invoice-head">
        <div>
            <h1>INVOICE</h1>
            <p><?= e($order['order_code']) ?></p>
        </div>
        <div>
            <a class="btn" href="<?= BASE_URL ?>/user/invoice-pdf?id=<?= $order['id'] ?>" target="_blank">Cetak PDF</a>
        </div>
    </div>
    <p><strong>Customer:</strong> <?= e($order['customer_name']) ?></p>
    <p><strong>Penerima:</strong> <?= e($order['recipient_name']) ?> - <?= e($order['phone']) ?></p>
    <p><strong>Alamat:</strong> <?= e($order['address_line']) ?>, <?= e($order['city']) ?>, <?= e($order['province']) ?> <?= e($order['postal_code']) ?></p>
    <table class="table">
        <thead><tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Total</th></tr></thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['product_name']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td><?= rupiah($item['product_price']) ?></td>
                    <td><?= rupiah($item['line_total']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><strong>Subtotal:</strong> <?= rupiah($order['subtotal']) ?></p>
    <p><strong>Ongkir:</strong> <?= rupiah($order['shipping_cost']) ?></p>
    <p><strong>Total:</strong> <?= rupiah($order['total']) ?></p>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>