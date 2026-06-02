<?php
use App\Controllers\AuthController;

guest_only();

$title = 'Reset Password';

$token = trim($_GET['token'] ?? ($_POST['token'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $token = trim($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirmation'] ?? '';

    if ($token === '') {
        flash('error', 'Token reset password tidak valid.');
        redirect('/forgot-password');
    }

    if (strlen($password) < 8) {
        flash('error', 'Password minimal 8 karakter.');
        redirect('/reset-password?token=' . urlencode($token));
    }

    if ($password !== $confirm) {
        flash('error', 'Konfirmasi password tidak sama.');
        redirect('/reset-password?token=' . urlencode($token));
    }

    $auth = new AuthController($pdo);

    if (!$auth->resetPassword($token, $password)) {
        flash('error', 'Link reset password tidak valid atau sudah kedaluwarsa.');
        redirect('/forgot-password');
    }

    flash('success', 'Password berhasil diubah. Silakan login dengan password baru.');
    redirect('/login');
}

require BASE_PATH . '/views/layouts/header.php';
?>
<style>
/* FORCE STYLE FOR RESET PASSWORD PAGE */
.reset-page{
  min-height:calc(100vh - 180px);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 0 70px;
}

.reset-wrap{
  width:min(1120px, 100%);
  display:grid;
  grid-template-columns:1.05fr .95fr;
  background:#fff;
  border:1px solid #f3dce8;
  border-radius:34px;
  overflow:hidden;
  box-shadow:0 30px 80px rgba(17,24,39,.10);
}

.reset-left{
  position:relative;
  padding:58px 54px;
  background:
    radial-gradient(circle at top left, rgba(236,20,140,.20), transparent 34%),
    linear-gradient(135deg,#fff 0%,#fff7fb 55%,#fdeaf4 100%);
  overflow:hidden;
}

.reset-left::after{
  content:"";
  position:absolute;
  right:-90px;
  bottom:-90px;
  width:280px;
  height:280px;
  border-radius:50%;
  background:linear-gradient(135deg,rgba(236,20,140,.25),rgba(217,15,127,.05));
}

.reset-badge{
  position:relative;
  z-index:1;
  display:inline-flex;
  align-items:center;
  gap:9px;
  padding:10px 16px;
  margin-bottom:22px;
  border-radius:999px;
  background:#fff;
  border:1px solid #f3bfd7;
  color:#d90f7f;
  font-size:.9rem;
  font-weight:700;
  box-shadow:0 12px 26px rgba(236,20,140,.10);
}

.reset-left h1{
  position:relative;
  z-index:1;
  margin:0 0 18px;
  font-family:'Playfair Display',serif;
  font-size:clamp(3.2rem,5vw,5.4rem);
  line-height:.96;
  color:#111827;
  letter-spacing:-.05em;
}

.reset-left h1 span{
  color:#ec148c;
}

.reset-left > p{
  position:relative;
  z-index:1;
  max-width:570px;
  margin:0 0 28px;
  color:#5f6c80;
  font-size:1.06rem;
  line-height:1.85;
}

.reset-rules{
  position:relative;
  z-index:1;
  display:grid;
  gap:14px;
  margin-top:26px;
}

.reset-rule{
  display:flex;
  align-items:center;
  gap:12px;
  padding:15px 16px;
  background:rgba(255,255,255,.82);
  border:1px solid #f3dce8;
  border-radius:20px;
  color:#182136;
  font-weight:700;
}

.reset-rule i{
  width:34px;
  height:34px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  background:#dcfce7;
  color:#166534;
  flex:0 0 auto;
}

.reset-right{
  display:flex;
  align-items:center;
  padding:58px 54px;
  background:#fff;
}

.reset-card{
  width:100%;
}

.reset-card h2{
  margin:0 0 10px;
  color:#182136;
  font-size:2.1rem;
  letter-spacing:-.03em;
}

.reset-card > p{
  margin:0 0 28px;
  color:#5f6c80;
  line-height:1.75;
}

.reset-form{
  display:grid;
  gap:14px;
}

.reset-form label{
  color:#182136;
  font-weight:700;
}

.reset-input{
  position:relative;
}

.reset-input i{
  position:absolute;
  left:18px;
  top:50%;
  transform:translateY(-50%);
  color:#ec148c;
}

.reset-input input{
  width:100%;
  min-height:58px;
  padding:14px 18px 14px 48px;
  border:1px solid #ecd8e4;
  border-radius:18px;
  background:#fff;
  color:#182136;
  font:inherit;
  outline:none;
  transition:.2s ease;
}

.reset-input input:focus{
  border-color:#ec148c;
  box-shadow:0 0 0 5px rgba(236,20,140,.10);
}

.reset-submit{
  width:100%;
  height:58px;
  margin-top:6px;
  border-radius:18px !important;
  font-size:1rem;
}

.reset-warning{
  padding:18px;
  border-radius:22px;
  background:#fff7fb;
  border:1px solid #f3dce8;
  color:#5f6c80;
  line-height:1.7;
}

.reset-warning h3{
  margin:0 0 8px;
  color:#182136;
  font-size:1.2rem;
}

.reset-warning .btn{
  margin-top:14px;
  width:100%;
  height:54px;
  border-radius:18px !important;
}

.reset-note{
  display:flex;
  gap:10px;
  align-items:flex-start;
  margin-top:18px;
  padding:15px;
  border-radius:18px;
  background:#fff7fb;
  border:1px solid #f3dce8;
  color:#5f6c80;
  line-height:1.6;
}

.reset-note i{
  color:#ec148c;
  margin-top:3px;
}

.reset-back{
  margin-top:18px;
  color:#5f6c80;
}

.reset-back a{
  color:#ec148c;
  font-weight:800;
}

.password-toggle{
  position:absolute;
  right:14px;
  top:50%;
  transform:translateY(-50%);
  border:0;
  background:transparent;
  color:#94a3b8;
  cursor:pointer;
  font-size:1rem;
}

.password-toggle:hover{
  color:#ec148c;
}

.reset-input.has-toggle input{
  padding-right:52px;
}

@media(max-width:920px){
  .reset-wrap{
    grid-template-columns:1fr;
  }

  .reset-left,
  .reset-right{
    padding:36px 24px;
  }
}

@media(max-width:560px){
  .reset-page{
    padding:20px 0 46px;
  }

  .reset-wrap{
    border-radius:24px;
  }

  .reset-left h1{
    font-size:2.8rem;
  }
}
</style>

<section class="reset-page">
  <div class="reset-wrap">

    <div class="reset-left">
      <div class="reset-badge">
        <i class="fa-solid fa-key"></i>
        Secure Password
      </div>

      <h1>Buat <span>Password Baru</span></h1>

      <p>
        Masukkan password baru yang kuat agar akun Beauty Care kamu tetap aman.
        Token reset tetap disimpan tersembunyi oleh sistem, jadi tidak ditampilkan di halaman.
      </p>

      <div class="reset-rules">
        <div class="reset-rule">
          <i class="fa-solid fa-check"></i>
          Minimal 8 karakter
        </div>

        <div class="reset-rule">
          <i class="fa-solid fa-check"></i>
          Gunakan kombinasi huruf dan angka
        </div>

        <div class="reset-rule">
          <i class="fa-solid fa-check"></i>
          Jangan gunakan password lama
        </div>
      </div>
    </div>

    <div class="reset-right">
      <div class="reset-card">
        <h2>Reset Password</h2>
        <p>Silakan buat password baru untuk akun kamu.</p>

        <?php if ($token === ''): ?>
          <div class="reset-warning">
            <h3>Link reset tidak valid</h3>
            <p>
              Link reset password tidak memiliki token atau sudah tidak lengkap.
              Silakan buat link reset password baru.
            </p>

            <a class="btn" href="<?= BASE_URL ?>/forgot-password">
              <i class="fa-solid fa-rotate-right"></i>
              Buat Link Reset Password
            </a>
          </div>
        <?php else: ?>
          <form method="post" class="reset-form">
            <?= csrf_input() ?>

            <input type="hidden" name="token" value="<?= e($token) ?>">

            <label for="password">Password Baru</label>
            <div class="reset-input has-toggle">
              <i class="fa-solid fa-lock"></i>
              <input
                id="password"
                type="password"
                name="password"
                placeholder="Minimal 8 karakter"
                required
                minlength="8"
                autocomplete="new-password"
              >
              <button class="password-toggle" type="button" data-target="password" aria-label="Lihat password">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>

            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <div class="reset-input has-toggle">
              <i class="fa-solid fa-lock-open"></i>
              <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Ulangi password baru"
                required
                minlength="8"
                autocomplete="new-password"
              >
              <button class="password-toggle" type="button" data-target="password_confirmation" aria-label="Lihat password">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>

            <button class="btn reset-submit" type="submit">
              <i class="fa-solid fa-check"></i>
              Reset Password
            </button>
          </form>

          <div class="reset-note">
            <i class="fa-solid fa-circle-info"></i>
            <span>Setelah password berhasil diubah, kamu akan diarahkan untuk login ulang.</span>
          </div>
        <?php endif; ?>

        <p class="reset-back">
          Ingat password?
          <a href="<?= BASE_URL ?>/login">Kembali ke login</a>
        </p>
      </div>
    </div>

  </div>
</section>

<script>
document.querySelectorAll('.password-toggle').forEach(function(button){
  button.addEventListener('click', function(){
    var targetId = this.getAttribute('data-target');
    var input = document.getElementById(targetId);
    var icon = this.querySelector('i');

    if (!input) return;

    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  });
});
</script>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
