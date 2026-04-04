<?php
$title = 'Checkout';
require_login();
if (empty($_SESSION['cart'])) redirect('/cart');

$addressStmt = $pdo->prepare('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC');
$addressStmt->execute([current_user_id()]);
$addresses = $addressStmt->fetchAll();
if (!$addresses) {
    flash('error', 'Isi alamat terlebih dahulu sebelum checkout.');
    redirect('/user/addresses');
}

$shippingRates = $pdo->query('SELECT * FROM shipping_rates WHERE is_active = 1 ORDER BY city ASC')->fetchAll();
$subtotal = cart_subtotal();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $addressId = (int) ($_POST['address_id'] ?? 0);
    $shippingId = (int) ($_POST['shipping_id'] ?? 0);
    $notes = trim($_POST['notes'] ?? '');

    $stmt = $pdo->prepare('SELECT * FROM shipping_rates WHERE id = ? AND is_active = 1 LIMIT 1');
    $stmt->execute([$shippingId]);
    $shipping = $stmt->fetch();
    if (!$shipping) die('Ongkir tidak valid.');

    foreach ($_SESSION['cart'] as $cartItem) {
        $stockStmt = $pdo->prepare('SELECT stock FROM products WHERE id = ? LIMIT 1');
        $stockStmt->execute([$cartItem['id']]);
        $dbProduct = $stockStmt->fetch();

        if (!$dbProduct || (int) $dbProduct['stock'] < (int) $cartItem['qty']) {
            flash('error', 'Checkout dibatalkan karena stok salah satu produk tidak mencukupi.');
            redirect('/cart');
        }
    }

    $orderCode = 'INV-' . date('YmdHis') . '-' . random_int(100, 999);
    $shippingCost = (float) $shipping['cost'];
    $total = $subtotal + $shippingCost;

    $pdo->beginTransaction();
    try {
        $orderStmt = $pdo->prepare('INSERT INTO orders(order_code,user_id,address_id,subtotal,shipping_cost,total,payment_method,payment_status,order_status,notes) VALUES(?,?,?,?,?,?,?,?,?,?)');
        $orderStmt->execute([$orderCode, current_user_id(), $addressId, $subtotal, $shippingCost, $total, 'bank_transfer', 'pending', 'new', $notes]);
        $orderId = $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('INSERT INTO order_items(order_id,product_id,product_name,product_price,qty,line_total) VALUES(?,?,?,?,?,?)');
        $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');

        foreach ($_SESSION['cart'] as $item) {
            $lineTotal = $item['price'] * $item['qty'];
            $itemStmt->execute([$orderId, $item['id'], $item['name'], $item['price'], $item['qty'], $lineTotal]);
            $stockStmt->execute([$item['qty'], $item['id'], $item['qty']]);

            if ($stockStmt->rowCount() === 0) {
                throw new RuntimeException('Stok gagal diperbarui.');
            }
        }

        $pdo->commit();
        unset($_SESSION['cart']);
        flash('success', 'Checkout berhasil. Silakan lakukan pembayaran.');
        redirect('/user/order-detail?id=' . $orderId);
    } catch (Throwable $e) {
        $pdo->rollBack();
        die('Checkout gagal: ' . $e->getMessage());
    }
}

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Checkout</h1>
<form method="post" class="card form-grid">
    <?= csrf_input() ?>
    <label>Pilih Alamat
        <select name="address_id" required>
            <?php foreach ($addresses as $address): ?>
                <option value="<?= $address['id'] ?>"><?= e($address['recipient_name']) ?> - <?= e($address['city']) ?>, <?= e($address['province']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Pilih Ongkir
        <select name="shipping_id" required>
            <?php foreach ($shippingRates as $rate): ?>
                <option value="<?= $rate['id'] ?>"><?= e($rate['label']) ?> - <?= e($rate['city']) ?> (<?= rupiah($rate['cost']) ?>)</option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Catatan
        <textarea name="notes"></textarea>
    </label>
    <p>Subtotal: <strong><?= rupiah($subtotal) ?></strong></p>
    <button class="btn">Buat Pesanan</button>
</form>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>