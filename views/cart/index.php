<?php
use App\Models\Cart;
use App\Models\CartItem;

$title = 'Cart';
require_login();

$cartModel = new Cart($pdo);
$cartItemModel = new CartItem($pdo);
$cart = $cartModel->getOrCreate(current_user_id());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? '';
    $productId = (int) ($_POST['id'] ?? 0);

    $productStmt = $pdo->prepare('SELECT id, stock FROM products WHERE id = ? LIMIT 1');
    $productStmt->execute([$productId]);
    $product = $productStmt->fetch();

    if ($action === 'update') {
        if (!$product) {
            $cartItemModel->remove((int) $cart['id'], $productId);
            flash('error', 'Produk tidak ditemukan dan dihapus dari keranjang.');
            redirect('/cart');
        }

        $qty = max(1, (int) ($_POST['qty'] ?? 1));
        if ($qty > (int) $product['stock']) {
            $qty = (int) $product['stock'];
            flash('error', 'Jumlah melebihi stok tersedia. Qty disesuaikan.');
        }

        if ($qty <= 0) {
            $cartItemModel->remove((int) $cart['id'], $productId);
        } else {
            $cartItemModel->updateQty((int) $cart['id'], $productId, $qty, (int) $product['stock']);
        }
    }

    if ($action === 'remove') {
        $cartItemModel->remove((int) $cart['id'], $productId);
    }

    redirect('/cart');
}

$items = $cartItemModel->byCart((int) $cart['id']);
require BASE_PATH . '/views/layouts/header.php';
?>
<h1 style="margin-bottom:18px;">Keranjang Belanja</h1>

<?php if (!$items): ?>
    <div class="card">
        <p style="margin:0;font-size:1.05rem;">Keranjang kamu masih kosong.</p>
    </div>
<?php else: ?>
    <div class="card">
        <table class="table">
            <thead>
                <tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Total</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td><?= e($item['name']) ?></td>
                    <td><?= rupiah($item['price_at_added']) ?></td>
                    <td>
                        <form method="post" class="inline-form">
                            <?= csrf_input() ?>
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= (int) $item['product_id'] ?>">
                            <input type="number" name="qty" min="1" max="<?= (int) ($item['stock'] ?? 9999) ?>" value="<?= (int) $item['qty'] ?>">
                            <button class="btn btn-sm">Update</button>
                        </form>
                    </td>
                    <td><?= rupiah($item['line_total']) ?></td>
                    <td>
                        <form method="post">
                            <?= csrf_input() ?>
                            <input type="hidden" name="action" value="remove">
                            <input type="hidden" name="id" value="<?= (int) $item['product_id'] ?>">
                            <button class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <h3>Subtotal: <?= rupiah(cart_subtotal()) ?></h3>
        <a class="btn" href="<?= BASE_URL ?>/checkout">Checkout</a>
    </div>
<?php endif; ?>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
