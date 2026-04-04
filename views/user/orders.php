<?php
$title = 'Pesanan Saya';
require_login();
$stmt = $pdo->prepare('SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC');
$stmt->execute([current_user_id()]);
$orders = $stmt->fetchAll();
require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Pesanan Saya</h1>
<div class="card">
    <table class="table">
        <thead><tr><th>Invoice</th><th>Total</th><th>Status</th><th>Pembayaran</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= e($order['order_code']) ?></td>
                <td><?= rupiah($order['total']) ?></td>
                <td><?= e($order['order_status']) ?></td>
                <td><?= e($order['payment_status']) ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/user/order-detail?id=<?= $order['id'] ?>">Detail</a> |
                    <a href="<?= BASE_URL ?>/user/invoice?id=<?= $order['id'] ?>">Invoice</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
