<?php
$title = 'Orders';
require_admin();

$status = trim($_GET['status'] ?? '');
$payment = trim($_GET['payment'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$where = ' WHERE 1=1 ';
$params = [];

if ($status !== '') {
    $where .= ' AND o.order_status = ? ';
    $params[] = $status;
}

if ($payment !== '') {
    $where .= ' AND o.payment_status = ? ';
    $params[] = $payment;
}

$countStmt = $pdo->prepare("SELECT COUNT(*) total FROM orders o $where");
$countStmt->execute($params);
$total = (int) $countStmt->fetch()['total'];
$totalPages = max(1, (int) ceil($total / $limit));

$stmt = $pdo->prepare("SELECT o.*, u.name AS user_name FROM orders o JOIN users u ON u.id = o.user_id $where ORDER BY o.id DESC LIMIT $limit OFFSET $offset");
$stmt->execute($params);
$orders = $stmt->fetchAll();

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Orders</h1>
<form method="get" class="toolbar">
    <select name="status">
        <option value="">Semua Status Order</option>
        <?php foreach (['new','processed','shipped','completed','cancelled'] as $s): ?>
            <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
    </select>

    <select name="payment">
        <option value="">Semua Status Pembayaran</option>
        <?php foreach (['pending','waiting_verification','paid','rejected'] as $p): ?>
            <option value="<?= $p ?>" <?= $payment === $p ? 'selected' : '' ?>><?= $p ?></option>
        <?php endforeach; ?>
    </select>

    <button class="btn">Filter</button>
</form>

<table class="table admin-table">
    <thead><tr><th>Invoice</th><th>User</th><th>Total</th><th>Order Status</th><th>Payment</th><th>Aksi</th></tr></thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?= e($order['order_code']) ?></td>
                <td><?= e($order['user_name']) ?></td>
                <td><?= rupiah($order['total']) ?></td>
                <td><?= order_status_badge($order['order_status']) ?></td>
                <td><?= order_status_badge($order['payment_status']) ?></td>
                <td><a href="<?= BASE_URL ?>/admin/order-detail?id=<?= $order['id'] ?>">Detail</a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="page-link <?= $i === $page ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/orders?status=<?= urlencode($status) ?>&payment=<?= urlencode($payment) ?>&page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>