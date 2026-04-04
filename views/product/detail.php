<?php
$title = 'Product Detail';
$slug = $_GET['slug'] ?? '';

$stmt = $pdo->prepare("
    SELECT p.*, b.name AS brand_name, c.name AS category_name
    FROM products p
    JOIN brands b ON b.id = p.brand_id
    JOIN categories c ON c.id = p.category_id
    WHERE p.slug = ?
    LIMIT 1
");
$stmt->execute([$slug]);
$product = $stmt->fetch();

if (!$product) {
    die('Produk tidak ditemukan');
}

$galleryStmt = $pdo->prepare('SELECT * FROM product_images WHERE product_id = ? ORDER BY is_primary DESC, id ASC');
$galleryStmt->execute([$product['id']]);
$gallery = $galleryStmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $qty = max(1, (int) ($_POST['qty'] ?? 1));

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $id = (int) $product['id'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$id] = [
            'id' => $id,
            'name' => $product['name'],
            'price' => (float) $product['price'],
            'qty' => $qty,
            'stock' => (int) $product['stock'],
            'image' => $product['image'],
        ];
    }

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

        <form method="post" class="inline-form">
            <?= csrf_input() ?>
            <input type="number" name="qty" min="1" value="1">
            <button class="btn">Tambah ke Keranjang</button>
        </form>
    </div>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>