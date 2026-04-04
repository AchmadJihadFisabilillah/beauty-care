<?php
$title = 'Home';
$stmt = $pdo->query("SELECT p.*, b.name AS brand_name FROM products p JOIN brands b ON b.id = p.brand_id WHERE p.is_active = 1 ORDER BY p.id DESC LIMIT 8");
$products = $stmt->fetchAll();
require BASE_PATH . '/views/layouts/header.php';
?>
<section class="hero card">
    <h1>Radiant Skin Starts Here</h1>
    <p>Luxury skincare dengan multi-brand catalog, checkout, dan pembayaran transfer.</p>
    <a class="btn" href="<?= BASE_URL ?>/catalog">Lihat Catalog</a>
</section>

<h2>Produk Terbaru</h2>
<div class="grid products-grid">
    <?php foreach ($products as $product): ?>
        <article class="card product-card">
            <?php if ($product['image']): ?>
                <img class="product-thumb" src="<?= BASE_URL ?>/uploads/products/<?= e($product['image']) ?>" alt="<?= e($product['name']) ?>">
            <?php endif; ?>
            <h3><?= e($product['name']) ?></h3>
            <p><?= e($product['brand_name']) ?></p>
            <strong><?= rupiah($product['price']) ?></strong>
            <a class="btn btn-outline" href="<?= BASE_URL ?>/product?slug=<?= e($product['slug']) ?>">Detail</a>
        </article>
    <?php endforeach; ?>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
