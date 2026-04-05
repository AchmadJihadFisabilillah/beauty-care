<?php
use App\Services\UploadService;

$title = 'Kelola Gallery Produk';
require_admin();

$productId = (int) ($_GET['id'] ?? 0);

$productStmt = $pdo->prepare('SELECT * FROM products WHERE id = ? LIMIT 1');
$productStmt->execute([$productId]);
$product = $productStmt->fetch();

if (!$product) {
    die('Produk tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';

    if ($action === 'upload') {
        try {
            $images = UploadService::uploadMultipleImages($_FILES['gallery'], UPLOAD_PRODUCT_DIR);
            foreach ($images as $index => $img) {
                $pdo->prepare('INSERT INTO product_images(product_id,image,is_primary) VALUES(?,?,?)')
                    ->execute([$productId, $img, 0]);
            }
            admin_log('Upload gallery product', ['product_id' => $productId, 'count' => count($images)]);
            flash('success', 'Gallery berhasil ditambahkan.');
        } catch (Throwable $e) {
            flash('error', $e->getMessage());
        }
        redirect('/admin/products/gallery?id=' . $productId);
    }

    if ($action === 'delete-image') {
        $imageId = (int) ($_POST['image_id'] ?? 0);
        $imgStmt = $pdo->prepare('SELECT * FROM product_images WHERE id = ? AND product_id = ? LIMIT 1');
        $imgStmt->execute([$imageId, $productId]);
        $img = $imgStmt->fetch();

        if ($img) {
            delete_file_if_exists(UPLOAD_PRODUCT_DIR . $img['image']);
            $pdo->prepare('DELETE FROM product_images WHERE id = ?')->execute([$imageId]);
            admin_log('Delete gallery image', ['product_id' => $productId, 'image_id' => $imageId]);
            flash('success', 'Gambar gallery dihapus.');
        }

        redirect('/admin/products/gallery?id=' . $productId);
    }

    if ($action === 'make-primary') {
        $imageId = (int) ($_POST['image_id'] ?? 0);
        $pdo->prepare('UPDATE product_images SET is_primary = 0 WHERE product_id = ?')->execute([$productId]);
        $pdo->prepare('UPDATE product_images SET is_primary = 1 WHERE id = ? AND product_id = ?')->execute([$imageId, $productId]);
        admin_log('Set primary gallery image', ['product_id' => $productId, 'image_id' => $imageId]);
        flash('success', 'Primary image diperbarui.');
        redirect('/admin/products/gallery?id=' . $productId);
    }
}

$galleryStmt = $pdo->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC');
$galleryStmt->execute([$productId]);
$gallery = $galleryStmt->fetchAll();

require BASE_PATH . '/views/layouts/admin_header.php';
?>
<h1>Gallery Produk - <?= e($product['name']) ?></h1>

<div class="card">
    <form method="post" enctype="multipart/form-data" class="form-grid">
        <?= csrf_input() ?>
        <input type="hidden" name="action" value="upload">
        <label>Upload Gallery
            <input type="file" name="gallery[]" multiple accept=".jpg,.jpeg,.png,.webp" required>
        </label>
        <button class="btn">Upload Gallery</button>
    </form>
</div>

<div class="grid products-grid">
    <?php foreach ($gallery as $img): ?>
        <div class="card">
            <img class="product-thumb" src="<?= BASE_URL ?>/uploads/products/<?= e($img['image']) ?>" alt="gallery">
            <p><?= $img['is_primary'] ? '<strong>Primary</strong>' : 'Gallery' ?></p>
            <div class="inline-form">
                <form method="post">
                    <?= csrf_input() ?>
                    <input type="hidden" name="action" value="make-primary">
                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                    <button class="btn btn-sm">Jadikan Primary</button>
                </form>

                <form method="post">
                    <?= csrf_input() ?>
                    <input type="hidden" name="action" value="delete-image">
                    <input type="hidden" name="image_id" value="<?= $img['id'] ?>">
                    <button class="btn btn-danger btn-sm" data-confirm="Hapus gambar ini?">Hapus</button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php require BASE_PATH . '/views/layouts/admin_footer.php'; ?>