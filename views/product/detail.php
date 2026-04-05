<?php
use App\Controllers\CartController;

$title = 'Product Detail';
$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("\n    SELECT p.*, b.name AS brand_name, c.name AS category_name\n    FROM products p\n    JOIN brands b ON b.id = p.brand_id\n    JOIN categories c ON c.id = p.category_id\n    WHERE p.slug = ?\n    LIMIT 1\n");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    die('Produk tidak ditemukan');
}

$galleryStmt = $pdo->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC');
$galleryStmt->execute([$product['id']]);
$gallery = $galleryStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_login();
    verify_csrf();
    $qty = max(1, (int) ($_POST['qty'] ?? 1));

    if ($qty > (int) $product['stock']) {
        $qty = (int) $product['stock'];
    }

    if ($qty <= 0) {
        flash('error', 'Stok produk tidak tersedia.');
        redirect('/product?slug=' . urlencode($slug));
    }

    (new CartController())->add($pdo, $product, $qty);
    flash('success', 'Produk ditambahkan ke keranjang.');
    redirect('/cart');
}

require BASE_PATH . '/views/layouts/header.php';
?>
<div class="card detail-grid">
    <div>
        <?php if ($product['image']): ?>
            <img class="detail-image" src="<?= BASE_URL ?>/uploads/products/<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
        <?php endif; ?>

        <?php if ($gallery): ?>
            <div class="gallery-grid">
                <?php foreach ($gallery as $img): ?>
                    <img class="gallery-thumb" src="<?= BASE_URL ?>/uploads/products/<?= e($img['image']) ?>" alt="<?= e($product['name']) ?>">
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div>
        <h1><?= e($product['name']) ?></h1>
        <p>Brand: <?= e($product['brand_name']) ?></p>
        <p>Kategori: <?= e($product['category_name']) ?></p>
        <p>Stok: <?= (int) $product['stock'] ?></p>
        <strong><?= rupiah($product['price']) ?></strong>
        <div class="desc-box"><?= nl2br(e($product['description'])) ?></div>

        <?php if (is_logged_in()): ?>
            <form method="post" class="inline-form">
                <?= csrf_input() ?>
                <input type="number" name="qty" min="1" max="<?= (int) $product['stock'] ?>" value="1">
                <button class="btn">Tambah ke Keranjang</button>
            </form>
        <?php else: ?>
            <p><a class="btn" href="<?= BASE_URL ?>/login">Login untuk membeli</a></p>
        <?php endif; ?>
    </div>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
