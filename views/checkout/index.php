<?php
use App\Models\Cart;
use App\Models\CartItem;

$title = 'Checkout';
require_login();

$cartModel = new Cart($pdo);
$cartItemModel = new CartItem($pdo);
$cart = $cartModel->findByUser(current_user_id());

if (!$cart) {
    flash('error', 'Keranjang kamu masih kosong.');
    redirect('/cart');
}

$items = $cartItemModel->byCart((int) $cart['id']);

if (!$items) {
    flash('error', 'Keranjang kamu masih kosong.');
    redirect('/cart');
}

$addressStmt = $pdo->prepare('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC');
$addressStmt->execute([current_user_id()]);
$addresses = $addressStmt->fetchAll();

if (!$addresses) {
    flash('error', 'Isi alamat terlebih dahulu sebelum checkout.');
    redirect('/user/addresses');
}

// Load all active shipping rates for JS lookup
$shippingRatesRaw = $pdo->query('SELECT * FROM shipping_rates WHERE is_active = 1 ORDER BY city ASC')->fetchAll(PDO::FETCH_ASSOC);
$shippingRatesJson = json_encode($shippingRatesRaw, JSON_UNESCAPED_UNICODE);

$subtotal = cart_subtotal();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $addressId    = (int) ($_POST['address_id'] ?? 0);
    $paymentMethod = trim($_POST['payment_method'] ?? 'bank_transfer');
    $notes        = trim($_POST['notes'] ?? '');
    $shippingCost = (float) ($_POST['shipping_cost'] ?? 0);

    if (!in_array($paymentMethod, ['bank_transfer', 'ewallet', 'qris', 'cod'], true)) {
        flash('error', 'Metode pembayaran tidak valid.');
        redirect('/checkout');
    }

    $addressStmt = $pdo->prepare('SELECT * FROM addresses WHERE id = ? AND user_id = ? LIMIT 1');
    $addressStmt->execute([$addressId, current_user_id()]);
    $address = $addressStmt->fetch();

    if (!$address) {
        die('Alamat tidak valid.');
    }

    // Try to find ongkir from shipping_rates by city
    $cityLower = strtolower(trim($address['city']));
    $rateStmt = $pdo->prepare('SELECT cost FROM shipping_rates WHERE LOWER(TRIM(city)) = ? AND is_active = 1 LIMIT 1');
    $rateStmt->execute([$cityLower]);
    $rateRow = $rateStmt->fetch();
    if ($rateRow) {
        $shippingCost = (float) $rateRow['cost'];
    }
    // If no match, use posted value (may be 0 — admin will adjust later)

    foreach ($items as $cartItem) {
        $stockStmt = $pdo->prepare('SELECT stock, is_active FROM products WHERE id = ? LIMIT 1');
        $stockStmt->execute([$cartItem['product_id']]);
        $dbProduct = $stockStmt->fetch();

        if (
            !$dbProduct ||
            (int) $dbProduct['is_active'] !== 1 ||
            (int) $dbProduct['stock'] < (int) $cartItem['qty']
        ) {
            flash('error', 'Checkout dibatalkan karena stok salah satu produk tidak mencukupi.');
            redirect('/cart');
        }
    }

    $orderCode = 'INV-' . date('YmdHis') . '-' . random_int(100, 999);
    $total = $subtotal + $shippingCost;

    $pdo->beginTransaction();

    try {
        $orderStmt = $pdo->prepare('
            INSERT INTO orders(
                order_code,
                user_id,
                address_id,
                recipient_name_snapshot,
                phone_snapshot,
                address_line_snapshot,
                city_snapshot,
                province_snapshot,
                postal_code_snapshot,
                subtotal,
                shipping_cost,
                courier,
                shipping_service,
                total,
                payment_method,
                payment_status,
                order_status,
                notes
            ) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
        ');

        $orderStmt->execute([
            $orderCode,
            current_user_id(),
            $addressId,
            $address['recipient_name'],
            $address['phone'],
            $address['address_line'],
            $address['city'],
            $address['province'],
            $address['postal_code'],
            $subtotal,
            $shippingCost,
            null, // courier — admin will fill later
            null, // shipping_service — admin will fill later
            $total,
            $paymentMethod,
            'pending',
            'new',
            $notes,
        ]);

        $orderId = (int) $pdo->lastInsertId();

        $itemStmt = $pdo->prepare('
            INSERT INTO order_items(
                order_id,
                product_id,
                product_name,
                product_price,
                qty,
                line_total
            ) VALUES(?,?,?,?,?,?)
        ');

        $stockStmt = $pdo->prepare('UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?');
        $movementStmt = $pdo->prepare("
            INSERT INTO stock_movements(product_id, movement_type, qty, note, created_by)
            VALUES(?, 'out', ?, ?, ?)
        ");

        foreach ($items as $item) {
            $lineTotal = (float) $item['price_at_added'] * (int) $item['qty'];

            $itemStmt->execute([
                $orderId,
                $item['product_id'],
                $item['name'],
                $item['price_at_added'],
                $item['qty'],
                $lineTotal
            ]);

            $stockStmt->execute([$item['qty'], $item['product_id'], $item['qty']]);

            if ($stockStmt->rowCount() === 0) {
                throw new RuntimeException('Stok gagal diperbarui.');
            }

            $movementStmt->execute([
                $item['product_id'],
                $item['qty'],
                'Pengurangan stok otomatis untuk order ' . $orderCode,
                current_user_id(),
            ]);
        }

        $cartModel->clear((int) $cart['id']);
        $pdo->commit();

        flash('success', 'Checkout berhasil. Silakan lanjutkan pembayaran sesuai metode yang dipilih.');
        redirect('/user/order-detail?id=' . $orderId);
    } catch (Throwable $e) {
        $pdo->rollBack();
        die('Checkout gagal: ' . $e->getMessage());
    }
}

require BASE_PATH . '/views/layouts/header.php';
?>

<h1>Checkout</h1>

<form method="post" class="card form-grid" id="checkoutForm">
    <?= csrf_input() ?>

    <label>Pilih Alamat
        <select name="address_id" id="address_id" required>
            <?php foreach ($addresses as $address): ?>
                <option
                    value="<?= $address['id'] ?>"
                    data-city="<?= e(strtolower(trim($address['city']))) ?>"
                >
                    <?= e($address['recipient_name']) ?> — <?= e($address['city']) ?>, <?= e($address['province']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>

    <label>Ongkos Kirim
        <input type="text" id="shipping_cost_display" readonly placeholder="Otomatis dari data kota">
    </label>
    <input type="hidden" name="shipping_cost" id="shipping_cost_value" value="0">

    <small id="ongkir_note" style="color:#64748b;margin-top:-10px;display:block;">
        Ongkir dihitung otomatis. Jika kota belum tercakup, admin akan menyesuaikan setelah pesanan dibuat.
    </small>

    <label>Metode Pembayaran
        <select name="payment_method" required>
            <option value="bank_transfer">Transfer Bank</option>
            <option value="ewallet">E-Wallet</option>
            <option value="qris">QRIS</option>
            <option value="cod">COD</option>
        </select>
    </label>

    <label>Catatan
        <textarea name="notes"></textarea>
    </label>

    <div class="card" style="background:#fafafa;">
        <h3>Ringkasan Belanja</h3>

        <table class="table">
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?= e($item['name']) ?></td>
                        <td><?= (int) $item['qty'] ?></td>
                        <td><?= rupiah($item['price_at_added']) ?></td>
                        <td><?= rupiah($item['line_total']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="checkout-total">
            <p>Subtotal: <strong><?= rupiah($subtotal) ?></strong></p>
            <p>Ongkir: <strong id="ongkirText">—</strong></p>
            <hr>
            <h3>Total Bayar: <strong id="grandTotalText"><?= rupiah($subtotal) ?></strong></h3>
        </div>

        <input type="hidden" id="subtotal" value="<?= $subtotal ?>">
    </div>

    <button class="btn">Buat Pesanan</button>
</form>

<script>
const addressSelect = document.getElementById('address_id');
const shippingDisplay = document.getElementById('shipping_cost_display');
const shippingValue  = document.getElementById('shipping_cost_value');
const ongkirText     = document.getElementById('ongkirText');
const grandTotalText = document.getElementById('grandTotalText');
const subtotal       = parseInt(document.getElementById('subtotal').value || 0);
const ongkirNote     = document.getElementById('ongkir_note');

// Shipping rates from PHP (array of {id, label, city, cost})
const shippingRates = <?= $shippingRatesJson ?>;

function formatRupiah(number) {
    return 'Rp' + Number(number).toLocaleString('id-ID');
}

function updateOngkir() {
    const selectedOption = addressSelect.options[addressSelect.selectedIndex];
    const city = (selectedOption ? selectedOption.dataset.city || '' : '').trim().toLowerCase();

    // Normalize: remove 'kota ', 'kabupaten ', 'kab. ' prefix for matching
    const cityNorm = city.replace(/^(kota |kabupaten |kab\. )/i, '').trim();

    const rate = shippingRates.find(r => {
        const rc = r.city.trim().toLowerCase().replace(/^(kota |kabupaten |kab\. )/i, '');
        return rc === cityNorm || rc === city;
    });

    if (rate) {
        const cost = parseFloat(rate.cost);
        shippingDisplay.value = formatRupiah(cost);
        shippingValue.value   = cost;
        ongkirText.textContent = formatRupiah(cost);
        grandTotalText.textContent = formatRupiah(subtotal + cost);
        ongkirNote.textContent = 'Ongkir dihitung berdasarkan kota tujuan.';
        ongkirNote.style.color = '#16a34a';
    } else {
        shippingDisplay.value = 'Belum tersedia';
        shippingValue.value   = 0;
        ongkirText.textContent = 'Ditentukan admin';
        grandTotalText.textContent = formatRupiah(subtotal);
        ongkirNote.textContent = 'Ongkir untuk kota ini akan ditentukan oleh admin setelah pesanan dibuat.';
        ongkirNote.style.color = '#f59e0b';
    }
}

// Run on page load
updateOngkir();

addressSelect.addEventListener('change', updateOngkir);
</script>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>