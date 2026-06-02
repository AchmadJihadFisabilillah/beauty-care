<?php
$title = 'Sales Reports';
require_admin();

$salesRows = $pdo->query("
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
    ORDER BY created_at ASC, id ASC
")->fetchAll();

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Laporan Penjualan</h1>

<div class="toolbar">
    <a class="btn" href="<?= BASE_URL ?>/admin/reports-export">Export CSV</a>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Kode Order</th>
                <th>Pembeli</th>
                <th>Total Sales</th>
                <th>Status Order</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($salesRows)): ?>
                <tr>
                    <td colspan="5">Belum ada penjualan dengan status pembayaran paid.</td>
                </tr>
            <?php endif; ?>

            <?php foreach ($salesRows as $row): ?>
                <tr>
                    <td><?= e(date('d M Y H:i', strtotime($row['created_at']))) ?></td>
                    <td><?= e($row['order_code']) ?></td>
                    <td><?= e($row['recipient_name_snapshot']) ?></td>
                    <td><?= rupiah($row['total']) ?></td>
                    <td><?= e($row['order_status']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>