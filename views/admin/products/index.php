<?php
$title = 'Manage Products';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);

        // 1. CEK APAKAH PRODUK SEDANG DALAM PESANAN AKTIF
        // Pastikan status ini sama persis dengan yang ada di database kamu
        $checkStmt = $pdo->prepare("
            SELECT COUNT(oi.id) 
            FROM order_items oi
            JOIN orders o ON oi.order_id = o.id
            WHERE oi.product_id = ? 
            AND o.order_status NOT IN ('completed', 'canceled', 'rejected')
        ");
        $checkStmt->execute([$id]);
        $activeOrderCount = $checkStmt->fetchColumn();

        // 2. TAMPILKAN NOTIFIKASI JIKA PRODUK LAGI ADA YANG PESAN
        if ($activeOrderCount > 0) {
            flash('error', 'Gagal dihapus: Produk tersebut lagi ada yang pesan dan transaksinya belum selesai.');
            redirect('/admin/products');
            exit; 
        }

        // Jika aman (tidak ada pesanan aktif), lanjutkan hapus paksa
        try {
            $stmt = $pdo->prepare('SELECT image FROM products WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $product = $stmt->fetch();

            if ($product) {
                $galleryStmt = $pdo->prepare('SELECT image FROM product_images WHERE product_id = ?');
                $galleryStmt->execute([$id]);
                $galleryImages = $galleryStmt->fetchAll();

                // Matikan pengecekan perlindungan Foreign Key sementara
                $pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

                // Eksekusi hapus produk secara paksa
                $pdo->prepare('DELETE FROM products WHERE id = ?')->execute([$id]);

                // Nyalakan kembali perlindungan database
                $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

                // Hapus file gambar secara fisik
                if (!empty($product['image'])) {
                    delete_file_if_exists(UPLOAD_PRODUCT_DIR . $product['image']);
                }

                foreach ($galleryImages as $img) {
                    delete_file_if_exists(UPLOAD_PRODUCT_DIR . $img['image']);
                }

                admin_log('Delete product', ['product_id' => $id]);
                flash('success', 'Produk berhasil dihapus permanen.');
            }
        } catch (PDOException $e) {
            // Pastikan pelindung database tetap dinyalakan jika terjadi error lain
            $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
            flash('error', 'Terjadi kesalahan sistem saat menghapus: ' . $e->getMessage());
        }

        redirect('/admin/products');
        exit;
    }
}

$q = trim($_GET['q'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$countStmt = $pdo->prepare('SELECT COUNT(*) total FROM products WHERE name LIKE ?');
$countStmt->execute(["%$q%"]);
$total = (int)$countStmt->fetch()['total'];
$totalPages = max(1, (int)ceil($total / $limit));

$stmt = $pdo->prepare("
    SELECT p.*, b.name AS brand_name, c.name AS category_name
    FROM products p
    LEFT JOIN brands b ON b.id = p.brand_id
    LEFT JOIN categories c ON c.id = p.category_id
    WHERE p.name LIKE ?
    ORDER BY p.id DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute(["%$q%"]);
$products = $stmt->fetchAll();

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Products</h1>
<div class="toolbar">
    <form method="get">
        <input type="text" name="q" value="<?= e($q) ?>" placeholder="Cari produk...">
        <button class="btn">Cari</button>
    </form>
    <a class="btn" href="<?= BASE_URL ?>/admin/products/create">Tambah Produk</a>
</div>

<table class="table admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Brand</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Kadaluarsa</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= e($product['name']) ?></td>
                <td><?= e($product['brand_name']) ?></td>
                <td><?= e($product['category_name']?? 'Tanpa Kategori') ?></td>
                <td><?= rupiah($product['price']) ?></td>
                <td><?= $product['stock'] ?></td>
                <td>
                    <?php if (!empty($product['expired_date'])): ?>
                        <?php if (strtotime($product['expired_date']) < strtotime(date('Y-m-d'))): ?>
                            <span class="badge badge-red">
                                Expired: <?= e(date('d-m-Y', strtotime($product['expired_date']))) ?>
                            </span>
                        <?php else: ?>
                            <span class="badge badge-green">
                                <?= e(date('d-m-Y', strtotime($product['expired_date']))) ?>
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td><?= $product['is_active'] ? '<span class="badge badge-green">Aktif</span>' : '<span class="badge badge-red">Nonaktif</span>' ?></td>
                <td>
                    <a href="<?= BASE_URL ?>/admin/products/edit?id=<?= $product['id'] ?>">Edit</a> |
                    <a href="<?= BASE_URL ?>/admin/products/stock?id=<?= $product['id'] ?>">Stok</a> |
                    <a href="<?= BASE_URL ?>/admin/products/gallery?id=<?= $product['id'] ?>">Gallery</a>
                    
                    <form method="post" class="inline-form" style="display:inline-flex" onsubmit="return confirm('Yakin ingin menghapus produk ini secara permanen?');">
                        <?= csrf_input() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a class="page-link <?= $i === $page ? 'active' : '' ?>" href="<?= BASE_URL ?>/admin/products?q=<?= urlencode($q) ?>&page=<?= $i ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>

<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>