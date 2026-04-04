<?php
require_login();
use App\Services\InvoiceService;

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

ob_start();
?>
<html>
<head>
<style>
body{font-family:DejaVu Sans,sans-serif;font-size:12px;} table{width:100%;border-collapse:collapse;} th,td{border:1px solid #ccc;padding:8px;} h1{margin-bottom:0;} .total{margin-top:20px;text-align:right;}
</style>
</head>
<body>
<h1>INVOICE</h1>
<p><?= e($order['order_code']) ?></p>
<p>Customer: <?= e($order['customer_name']) ?></p>
<p>Penerima: <?= e($order['recipient_name']) ?> - <?= e($order['phone']) ?></p>
<p>Alamat: <?= e($order['address_line']) ?>, <?= e($order['city']) ?>, <?= e($order['province']) ?> <?= e($order['postal_code']) ?></p>
<table>
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
<div class="total">
<p>Subtotal: <?= rupiah($order['subtotal']) ?></p>
<p>Ongkir: <?= rupiah($order['shipping_cost']) ?></p>
<p><strong>Total: <?= rupiah($order['total']) ?></strong></p>
</div>
</body>
</html>
<?php
$html = ob_get_clean();
InvoiceService::streamPdf($html, 'invoice-' . $order['order_code'] . '.pdf');