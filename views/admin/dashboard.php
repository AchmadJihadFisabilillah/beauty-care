<?php
$title = 'Admin Dashboard';
require_admin();

$totalSales = $pdo->query("SELECT COALESCE(SUM(total),0) total FROM orders WHERE payment_status='paid'")->fetch()['total'];
$totalOrders = $pdo->query("SELECT COUNT(*) total FROM orders")->fetch()['total'];
$totalProducts = $pdo->query("SELECT COUNT(*) total FROM products")->fetch()['total'];
$pendingPayments = $pdo->query("SELECT COUNT(*) total FROM payment_confirmations WHERE verification_status='pending'")->fetch()['total'];
$totalUsers = $pdo->query("SELECT COUNT(*) total FROM users WHERE role='user'")->fetch()['total'];

$latestOrders = $pdo->query("
    SELECT o.order_code, o.total, o.order_status, o.payment_status, u.name AS user_name
    FROM orders o
    JOIN users u ON u.id = o.user_id
    ORDER BY o.id DESC
    LIMIT 5
")->fetchAll();

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Dashboard</h1>

<div class="admin-stats">
    <div class="stat-card"><h3>Total Sales</h3><p><?= rupiah($totalSales) ?></p></div>
    <div class="stat-card"><h3>Total Orders</h3><p><?= $totalOrders ?></p></div>
    <div class="stat-card"><h3>Total Products</h3><p><?= $totalProducts ?></p></div>
    <div class="stat-card"><h3>Pending Payments</h3><p><?= $pendingPayments ?></p></div>
    <div class="stat-card"><h3>Total Users</h3><p><?= $totalUsers ?></p></div>
</div>

<div class="card">
    <h3>Order Terbaru</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Invoice</th>
                <th>User</th>
                <th>Total</th>
                <th>Order</th>
                <th>Payment</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($latestOrders as $order): ?>
                <tr>
                    <td><?= e($order['order_code']) ?></td>
                    <td><?= e($order['user_name']) ?></td>
                    <td><?= rupiah($order['total']) ?></td>
                    <td><?= order_status_badge($order['order_status']) ?></td>
                    <td><?= order_status_badge($order['payment_status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>