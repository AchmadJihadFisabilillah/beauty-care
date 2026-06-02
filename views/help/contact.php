<?php
$title = 'Contact Me';
require BASE_PATH . '/views/layouts/header.php';

$whatsappNumber = '6281234567890'; // Ganti dengan nomor WhatsApp kamu, format: 62xxxxxxxxxx
$whatsappText = rawurlencode('Halo Beauty Care, saya ingin bertanya tentang produk skincare.');
$whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappText}";
?>

<section class="contact-page">
    <div class="contact-hero card">
        <span class="hero-badge">Beauty Care Support</span>
        <h1>Contact Me</h1>
        <p>
            Punya pertanyaan tentang produk, cara order, pembayaran, atau pengiriman?
            Cek contoh pertanyaan dan jawaban di bawah, lalu hubungi kami lewat WhatsApp.
        </p>

        <a class="btn contact-wa-btn" href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            Chat via WhatsApp
        </a>
    </div>

    <div class="section-intro contact-intro">
        <span class="hero-badge">FAQ</span>
        <h2>Contoh Pertanyaan & Jawaban</h2>
        <p>Beberapa pertanyaan yang sering ditanyakan pelanggan Beauty Care.</p>
    </div>

    <div class="faq-grid">
        <div class="faq-card card">
            <h3>Apakah produk Beauty Care aman digunakan?</h3>
            <p>
                Ya, produk yang tersedia dipilih dari brand skincare terpercaya.
                Pastikan kamu membaca detail produk dan menyesuaikan dengan tipe kulitmu.
            </p>
        </div>

        <div class="faq-card card">
            <h3>Bagaimana cara membeli produk?</h3>
            <p>
                Pilih produk di katalog, klik detail produk, masukkan ke keranjang,
                lalu lanjutkan checkout dan isi data pengiriman.
            </p>
        </div>

        <div class="faq-card card">
            <h3>Metode pembayaran apa saja yang tersedia?</h3>
            <p>
                Pembayaran dapat mengikuti metode yang tersedia saat checkout.
                Setelah pembayaran, admin akan memverifikasi pesanan kamu.
            </p>
        </div>

        <div class="faq-card card">
            <h3>Kapan pesanan saya dikirim?</h3>
            <p>
                Pesanan akan diproses setelah pembayaran terverifikasi.
                Status pesanan dapat kamu cek melalui halaman akun atau invoice.
            </p>
        </div>

        <div class="faq-card card">
            <h3>Apakah bisa bertanya sebelum membeli?</h3>
            <p>
                Bisa. Kamu dapat klik tombol WhatsApp untuk konsultasi produk,
                stok, harga, atau rekomendasi sesuai kebutuhan kulit.
            </p>
        </div>

        <div class="faq-card card">
            <h3>Bagaimana jika produk tidak cocok?</h3>
            <p>
                Hentikan pemakaian terlebih dahulu. Kamu bisa menghubungi admin
                untuk meminta saran penggunaan atau rekomendasi produk lain.
            </p>
        </div>
    </div>

    <div class="contact-bottom card">
        <div>
            <h2>Masih ada pertanyaan lain?</h2>
            <p>Klik tombol di samping untuk langsung terhubung ke WhatsApp admin Beauty Care.</p>
        </div>

        <a class="btn contact-wa-btn" href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            Tanya Sekarang
        </a>
    </div>
</section>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
