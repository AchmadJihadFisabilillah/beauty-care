<?php
$title = 'Alamat';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = $_POST['action'] ?? 'create';

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $pdo->prepare('DELETE FROM addresses WHERE id = ? AND user_id = ?')->execute([$id, current_user_id()]);
        flash('success', 'Alamat berhasil dihapus.');
        redirect('/user/addresses');
    }

    $recipient = trim($_POST['recipient_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address_line'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $province = trim($_POST['province'] ?? '');
    $postal = trim($_POST['postal_code'] ?? '');
    $notes = trim($_POST['notes'] ?? '');
    $default = isset($_POST['is_default']) ? 1 : 0;

    if ($default) {
        $pdo->prepare('UPDATE addresses SET is_default = 0 WHERE user_id = ?')->execute([current_user_id()]);
    }

    $stmt = $pdo->prepare('INSERT INTO addresses(user_id,recipient_name,phone,address_line,city,province,postal_code,notes,is_default) VALUES(?,?,?,?,?,?,?,?,?)');
    $stmt->execute([current_user_id(), $recipient, $phone, $address, $city, $province, $postal, $notes, $default]);

    flash('success', 'Alamat berhasil ditambahkan.');
    redirect('/user/addresses');
}

$stmt = $pdo->prepare('SELECT * FROM addresses WHERE user_id = ? ORDER BY is_default DESC, id DESC');
$stmt->execute([current_user_id()]);
$addresses = $stmt->fetchAll();

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Alamat Saya</h1>
<div class="grid two-cols">
    <form method="post" class="card form-grid">
        <?= csrf_input() ?>
        <input type="hidden" name="action" value="create">
        <label>Nama Penerima<input type="text" name="recipient_name" required></label>
        <label>No HP<input type="text" name="phone" required></label>
        <label>Alamat<textarea name="address_line" required></textarea></label>
        <label>Kota<input type="text" name="city" required></label>
        <label>Provinsi<input type="text" name="province" required></label>
        <label>Kode Pos<input type="text" name="postal_code" required></label>
        <label>Catatan<textarea name="notes"></textarea></label>
        <label><input type="checkbox" name="is_default"> Jadikan default</label>
        <button class="btn">Simpan Alamat</button>
    </form>

    <div class="card">
        <?php foreach ($addresses as $address): ?>
            <div class="address-item">
                <strong><?= e($address['recipient_name']) ?> <?= $address['is_default'] ? '(Default)' : '' ?></strong>
                <p><?= e($address['phone']) ?></p>
                <p><?= e($address['address_line']) ?></p>
                <p><?= e($address['city']) ?>, <?= e($address['province']) ?> <?= e($address['postal_code']) ?></p>
                <div class="inline-form">
                    <a class="btn btn-sm btn-outline" href="<?= BASE_URL ?>/user/address-edit?id=<?= $address['id'] ?>">Edit</a>
                    <form method="post" class="inline-form">
                        <?= csrf_input() ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $address['id'] ?>">
                        <button class="btn btn-danger btn-sm" data-confirm="Hapus alamat ini?">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>