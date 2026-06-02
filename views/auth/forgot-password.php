<?php
use App\Controllers\AuthController;

guest_only();

$title = 'Lupa Password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();

    $email = trim($_POST['email'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Format email tidak valid.');
        redirect('/forgot-password');
    }

    $auth = new AuthController($pdo);
    $token = $auth->createPasswordResetToken($email);

    if ($token !== null) {
        $resetLink = BASE_URL . '/reset-password?token=' . urlencode($token);

        $subject = 'Reset Password Beauty Care';
        $message = "Halo,\n\n"
            . "Kami menerima permintaan reset password untuk akun kamu.\n\n"
            . "Klik link berikut untuk membuat password baru:\n"
            . $resetLink . "\n\n"
            . "Link ini hanya berlaku 30 menit.\n\n"
            . "Jika kamu tidak meminta reset password, abaikan email ini.";

        $headers = 'From: ' . env('MAIL_FROM', 'no-reply@localhost') . "\r\n";

        @mail($email, $subject, $message, $headers);

        // Untuk testing di localhost
        if (env('APP_ENV', 'production') === 'local') {
            flash('reset_link', $resetLink);
        }
    }

    flash('success', 'Jika email terdaftar, link reset password sudah dibuat.');
    redirect('/forgot-password');
}

$resetLink = flash('reset_link');

require BASE_PATH . '/views/layouts/header.php';
?>

<style>
/* FORCE STYLE FOR FORGOT PASSWORD PAGE */
.forgot-page{
  min-height:calc(100vh - 180px);
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 0 70px;
}

.forgot-wrap{
  width:min(1120px, 100%);
  display:grid;
  grid-template-columns:1.05fr .95fr;
  background:#fff;
  border:1px solid #f3dce8;
  border-radius:34px;
  overflow:hidden;
  box-shadow:0 30px 80px rgba(17,24,39,.10);
}

.forgot-left{
  position:relative;
  padding:58px 54px;
  background:
    radial-gradient(circle at top left, rgba(236,20,140,.20), transparent 34%),
    linear-gradient(135deg,#fff 0%,#fff7fb 55%,#fdeaf4 100%);
  overflow:hidden;
}

.forgot-left::after{
  content:"";
  position:absolute;
  right:-90px;
  bottom:-90px;
  width:280px;
  height:280px;
  border-radius:50%;
  background:linear-gradient(135deg,rgba(236,20,140,.25),rgba(217,15,127,.05));
}

.forgot-badge{
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

.forgot-left h1{
  position:relative;
  z-index:1;
  margin:0 0 18px;
  font-family:'Playfair Display',serif;
  font-size:clamp(3.2rem,5vw,5.4rem);
  line-height:.96;
  color:#111827;
  letter-spacing:-.05em;
}

.forgot-left h1 span{
  color:#ec148c;
}

.forgot-left > p{
  position:relative;
  z-index:1;
  max-width:570px;
  margin:0 0 28px;
  color:#5f6c80;
  font-size:1.06rem;
  line-height:1.85;
}

.forgot-steps{
  position:relative;
  z-index:1;
  display:grid;
  gap:14px;
  margin-top:24px;
}

.forgot-step{
  display:grid;
  grid-template-columns:48px 1fr;
  gap:15px;
  align-items:center;
  padding:16px;
  background:rgba(255,255,255,.82);
  border:1px solid #f3dce8;
  border-radius:22px;
}

.forgot-step strong{
  width:48px;
  height:48px;
  display:flex;
  align-items:center;
  justify-content:center;
  border-radius:50%;
  color:#fff;
  background:linear-gradient(135deg,#ec148c,#d90f7f);
  box-shadow:0 12px 24px rgba(236,20,140,.20);
}

.forgot-step h3{
  margin:0 0 4px;
  color:#182136;
  font-size:1rem;
}

.forgot-step p{
  margin:0;
  color:#5f6c80;
  font-size:.92rem;
  line-height:1.55;
}

.forgot-right{
  display:flex;
  align-items:center;
  padding:58px 54px;
  background:#fff;
}

.forgot-card{
  width:100%;
}

.forgot-card h2{
  margin:0 0 10px;
  color:#182136;
  font-size:2.1rem;
  letter-spacing:-.03em;
}

.forgot-card > p{
  margin:0 0 28px;
  color:#5f6c80;
  line-height:1.75;
}

.forgot-form{
  display:grid;
  gap:14px;
}

.forgot-form label{
  color:#182136;
  font-weight:700;
}

.forgot-input{
  position:relative;
}

.forgot-input i{
  position:absolute;
  left:18px;
  top:50%;
  transform:translateY(-50%);
  color:#ec148c;
}

.forgot-input input{
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

.forgot-input input:focus{
  border-color:#ec148c;
  box-shadow:0 0 0 5px rgba(236,20,140,.10);
}

.forgot-submit{
  width:100%;
  height:58px;
  margin-top:6px;
  border-radius:18px !important;
  font-size:1rem;
}

.forgot-note{
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

.forgot-note i{
  color:#ec148c;
  margin-top:3px;
}

.forgot-back{
  margin-top:18px;
  color:#5f6c80;
}

.forgot-back a{
  color:#ec148c;
  font-weight:800;
}

@media(max-width:920px){
  .forgot-wrap{
    grid-template-columns:1fr;
  }

  .forgot-left,
  .forgot-right{ 
    padding:36px 24px;
  }
}

@media(max-width:560px){
  .forgot-page{
    padding:20px 0 46px;
  }

  .forgot-wrap{
    border-radius:24px;
  }

  .forgot-left h1{
    font-size:2.8rem;
  }

  .forgot-step{
    grid-template-columns:42px 1fr;
  }

  .forgot-step strong{
    width:42px;
    height:42px;
  }
}
</style>

<section class="forgot-page">
  <div class="forgot-wrap">

    <div class="forgot-left">
      <div class="forgot-badge">
        <i class="fa-solid fa-shield-heart"></i>
        Secure Reset
      </div>

      <h1>Lupa <span>Password?</span></h1>

      <p>
        Tenang, kamu bisa membuat password baru dengan aman.
        Masukkan email akunmu, lalu gunakan link reset yang dibuat oleh sistem.
      </p>

      <div class="forgot-steps">
        <div class="forgot-step">
          <strong>1</strong>
          <div>
            <h3>Masukkan email</h3>
            <p>Gunakan email yang sudah terdaftar di akun Beauty Care.</p>
          </div>
        </div>

        <div class="forgot-step">
          <strong>2</strong>
          <div>
            <h3>Buka link reset</h3>
            <p>Link reset hanya berlaku 30 menit demi keamanan akun.</p>
          </div>
        </div>

        <div class="forgot-step">
          <strong>3</strong>
          <div>
            <h3>Buat password baru</h3>
            <p>Login kembali memakai password baru yang sudah kamu simpan.</p>
          </div>
        </div>
      </div>
    </div>

    <div class="forgot-right">
      <div class="forgot-card">
        <h2>Reset Password</h2>
        <p>Masukkan email akun Beauty Care kamu untuk membuat link reset password.</p>

        <form method="post" class="forgot-form">
          <?= csrf_input() ?>

          <label for="email">Email</label>
          <div class="forgot-input">
            <i class="fa-regular fa-envelope"></i>
            <input
              id="email"
              type="email"
              name="email"
              placeholder="nama@email.com"
              required
              autocomplete="email"
            >
          </div>

          <button class="btn forgot-submit" type="submit">
            <i class="fa-solid fa-paper-plane"></i>
            Buat Link Reset
          </button>
        </form>

        <?php if (!empty($resetLink)): ?>
        <div class="forgot-note" style="flex-direction: column; align-items: stretch; gap: 12px;">
          <div style="display: flex; gap: 10px;">
            <i class="fa-solid fa-link"></i>
            <span>Tombol reset berhasil dibuat:</span>
          </div>
          <a href="<?= e($resetLink) ?>" class="btn" style="text-align: center; text-decoration: none; padding: 12px; border-radius: 14px; font-size: 0.95rem;">
            Ke Halaman Reset Password
          </a>
        </div>
        <?php endif; ?>
        <div class="forgot-note">
          <i class="fa-solid fa-circle-info"></i>
          <span>Link reset akan kedaluwarsa dalam 30 menit demi keamanan akun.</span>
        </div>

        <p class="forgot-back">
          Ingat password?
          <a href="<?= BASE_URL ?>/login">Kembali ke login</a>
        </p>
      </div>
    </div>

  </div>
</section>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>