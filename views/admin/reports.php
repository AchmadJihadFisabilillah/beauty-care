<?php
$title = 'Sales Reports';
require_admin();

/*
  Grafik dibuat per transaksi/penjualan, bukan lagi per bulan.
  Dengan begitu tiap order paid menjadi 1 titik/bar di grafik sehingga tidak terlihat datar.
*/
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

$labels = [];
$values = [];

foreach ($salesRows as $row) {
    $labels[] = date('d M H:i', strtotime($row['created_at'])) . ' - ' . $row['order_code'];
    $values[] = (float) $row['total'];
}

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Laporan Penjualan</h1>

<div class="toolbar">
    <a class="btn" href="<?= BASE_URL ?>/admin/reports-export">Export CSV</a>
</div>

<div class="card">
    <h3 style="margin-bottom:16px;">Traffic Penjualan per Order</h3>
    <canvas id="salesChart" height="120"></canvas>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            label: 'Total per Penjualan',
            data: <?= json_encode($values) ?>,
            borderWidth: 2,
            tension: 0.35,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                display: true
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const value = context.parsed.y || 0;
                        return 'Total: Rp ' + value.toLocaleString('id-ID');
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 0,
                    autoSkip: true,
                    maxTicksLimit: 12
                }
            },
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + Number(value).toLocaleString('id-ID');
                    }
                }
            }
        }
    }
});
</script>
<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>
