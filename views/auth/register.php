<?php
$title = 'Register';
require_once BASE_PATH . '/app/helpers/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirmation'] ?? '';

    if ($password !== $confirm) {
        flash('error', 'Konfirmasi password tidak sama.');
        redirect('/register');
    }

    $check = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $check->execute([$email]);
    if ($check->fetch()) {
        flash('error', 'Email sudah digunakan.');
        redirect('/register');
    }

    $stmt = $pdo->prepare('INSERT INTO users(name,email,phone,password,role) VALUES(?,?,?,?,?)');
    $stmt->execute([$name, $email, $phone, password_hash($password, PASSWORD_DEFAULT), 'user']);
    flash('success', 'Register berhasil. Silakan login.');
    redirect('/login');
}

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Register</h1>
<form method="post" class="card form-grid">
    <?= csrf_input() ?>
    <label>Nama
        <input type="text" name="name" required>
    </label>
    <label>Email
        <input type="email" name="email" required>
    </label>
    <label>No HP
        <input type="text" name="phone" required>
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <label>Konfirmasi Password
        <input type="password" name="password_confirmation" required>
    </label>
    <button class="btn">Register</button>
</form>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
