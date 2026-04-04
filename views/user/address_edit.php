<?php
$title = 'Edit Alamat';
require_login();

$id = (int) ($_GET['id'] ?? 0);

$stmt = $pdo->prepare('SELECT * FROM addresses WHERE id = ? AND user_id = ? LIMIT 1');
$stmt->execute([$id, current_user_id()]);
$address = $stmt->fetch();

if (!$address) {
    die('Alamat tidak ditemukan.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $recipient = trim($_POST['recipient_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $addressLine = trim($_POST['address_line'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $postal = trim($_POST['postal_code'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $default = isset($_POST['is_default']) ? 1 : 0;

    if ($default) {
        $pdo->prepare('UPDATE addresses SET is_default = 0 WHERE user_id = ?')->execute([current_user_id()]);
    }

    $update = $pdo->prepare('UPDATE addresses SET recipient_name=?, phone=?, address_line=?, city=?, province=?, postal_code=?, notes=?, is_default=? WHERE id=? AND user_id=?');
    $update->execute([$recipient, $phone, $addressLine, $city, $province, $postal, $notes, $default, $id, current_user_id()]);

    flash('success', 'Alamat berhasil diperbarui.');
    redirect('/user/addresses');
}

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Edit Alamat</h1>
<form method="post" class="card form-grid">
    <?= csrf_input() ?>
    <label>Nama Penerima<input type="text" name="recipient_name" value="<?= e($address['recipient_name']) ?>" required></label>
    <label>No HP<input type="text" name="phone" value="<?= e($address['phone']) ?>" required></label>
    <label>Alamat<textarea name="address_line" required><?= e($address['address_line']) ?></textarea></label>
    <label>Kota<input type="text" name="city" value="<?= e($address['city']) ?>" required></label>
    <label>Provinsi<input type="text" name="province" value="<?= e($address['province']) ?>" required></label>
    <label>Kode Pos<input type="text" name="postal_code" value="<?= e($address['postal_code']) ?>" required></label>
    <label>Catatan<textarea name="notes"><?= e($address['notes']) ?></textarea></label>
    <label><input type="checkbox" name="is_default" <?= $address['is_default'] ? 'checked' : '' ?>> Jadikan default</label>
    <button class="btn">Update Alamat</button>
</form>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>