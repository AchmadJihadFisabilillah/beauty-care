<?php
use App\Controllers\AuthController;

guest_only();
$title = 'Login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $auth = new AuthController($pdo);
    if ($auth->attemptLogin($email, $password)) {
        flash('success', 'Login berhasil.');
        redirect(is_admin() ? '/admin/dashboard' : '/user/dashboard');
    }

    flash('error', 'Email atau password salah.');
    redirect('/login');
}

require BASE_PATH . '/views/layouts/header.php';
?>
<h1>Login</h1>
<form method="post" class="card form-grid">
    <?= csrf_input() ?>
    <label>Email
        <input type="email" name="email" required>
    </label>
    <label>Password
        <input type="password" name="password" required>
    </label>
    <button class="btn">Login</button>
</form>
<?php require BASE_PATH . '/views/layouts/footer.php'; ?>