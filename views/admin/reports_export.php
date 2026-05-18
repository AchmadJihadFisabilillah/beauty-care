<?php
require_admin();

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=sales-per-order-reports.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Tanggal', 'Kode Order', 'Pembeli', 'Total Sales', 'Payment Status', 'Order Status']);

$rows = $pdo->query("
    SELECT
        id,
        order_code,
        recipient_name_snapshot,
        total,
        payment_status,
        order_status,
        created_at
    FROM orders
    WHERE payment_status = 'paid'
    ORDER BY created_at DESC, id DESC
")->fetchAll();

admin_log('Export laporan penjualan per order', [
    'total_penjualan' => count($rows)
]);

foreach ($rows as $row) {
    fputcsv($output, [
        date('Y-m-d H:i:s', strtotime($row['created_at'])),
        $row['order_code'],
        $row['recipient_name_snapshot'],
        $row['total'],
        $row['payment_status'],
        $row['order_status']
    ]);
}

fclose($output);
exit;
