<?php
$title = 'Monthly Reports';
require_admin();

$monthlySales = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS month, COUNT(*) AS total_orders, COALESCE(SUM(total),0) AS total_sales
    FROM orders
    WHERE payment_status='paid'
    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
    ORDER BY month ASC
")->fetchAll();

$labels = [];
$values = [];

foreach ($monthlySales as $row) {
    $labels[] = $row['month'];
    $values[] = (float) $row['total_sales'];
}

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Laporan Bulanan</h1>

<div class="toolbar">
    <a class="btn" href="<?= BASE_URL ?>/admin/reports-export">Export CSV</a>
</div>

<div class="card">
    <canvas id="salesChart" height="120"></canvas>
</div>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Order</th>
                <th>Total Sales</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthlySales as $row): ?>
                <tr>
                    <td><?= e($row['month']) ?></td>
                    <td><?= $row['total_orders'] ?></td>
                    <td><?= rupiah($row['total_sales']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Total Sales',
            data: <?= json_encode($values) ?>,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                display: true
            }
        }
    }
});
</script>
<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>