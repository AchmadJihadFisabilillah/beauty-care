<?php
$title = 'Help & FAQ';
require BASE_PATH . '/views/layouts/header.php';

$whatsappNumber = defined('WHATSAPP_NUMBER') ? WHATSAPP_NUMBER : '6281234567890';
$whatsappText = rawurlencode('Halo Beauty Care, saya ingin bertanya tentang produk skincare.');
$whatsappUrl = "https://wa.me/{$whatsappNumber}?text={$whatsappText}";
?>

<section class="contact-page">
    <div class="contact-hero card">
        <span class="hero-badge"><i class="fa-solid fa-headset"></i> Beauty Care Support</span>
        <h1>How Can We Help You?</h1>
        <p>
            Punya pertanyaan seputar produk, cara order, pembayaran, atau pengiriman?
            Cari jawaban cepat di daftar FAQ kami di bawah ini, atau hubungi langsung admin kami via WhatsApp.
        </p>

        <a class="btn contact-wa-btn" href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            Tanya Admin via WhatsApp
        </a>
    </div>

    <div class="section-intro contact-intro">
        <span class="hero-badge">FAQ</span>
        <h2>Pertanyaan Sering Diajukan</h2>
        <p>Temukan solusi cepat dari pertanyaan yang paling sering diajukan pelanggan.</p>
        
        <!-- Premium Search Box -->
        <div class="faq-search-wrapper">
            <div class="faq-search-box">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="faqSearchInput" placeholder="Cari pertanyaan atau jawaban..." autocomplete="off">
                <button type="button" id="faqSearchClear" class="search-clear-btn" style="display: none;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Accordion FAQ Layout -->
    <div class="faq-accordion-container">
        <div class="faq-item card" data-category="umum">
            <div class="faq-question">
                <h3>Apakah produk Beauty Care aman digunakan?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Ya, semua produk yang tersedia di Beauty Care dipilih dari brand skincare terpercaya yang sudah terdaftar resmi (seperti BPOM).
                        Pastikan kamu membaca detail deskripsi produk dan menyesuaikannya dengan kondisi serta tipe kulitmu.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="order">
            <div class="faq-question">
                <h3>Bagaimana cara membeli produk?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Cukup jelajahi katalog di website kami, pilih produk yang kamu inginkan, klik tombol detail produk, dan masukkan ke keranjang belanja.
                        Setelah itu, masuk ke halaman keranjang (Cart), lakukan Checkout, isi informasi pengiriman dengan lengkap, dan lakukan pembayaran sesuai petunjuk.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="payment">
            <div class="faq-question">
                <h3>Metode pembayaran apa saja yang tersedia?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Kami mendukung metode pembayaran yang tercantum pada proses checkout (seperti transfer bank dan e-wallet). 
                        Setelah melakukan transfer, silakan upload bukti pembayaran kamu agar admin kami dapat segera melakukan verifikasi pesanan.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="shipping">
            <div class="faq-question">
                <h3>Kapan pesanan saya akan dikirim?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Pesanan kamu akan segera diproses dan dikirimkan setelah pembayaran terverifikasi oleh sistem kami.
                        Lama pengiriman tergantung kurir yang dipilih dan jarak lokasi pengiriman dari gudang kami di Bangkalan.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="umum">
            <div class="faq-question">
                <h3>Apakah bisa berkonsultasi sebelum membeli?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Tentu saja bisa! Kami sangat menyarankan konsultasi terlebih dahulu agar kamu mendapatkan produk yang benar-benar cocok.
                        Silakan hubungi kami dengan mengklik tombol WhatsApp untuk langsung berkonsultasi dengan admin kecantikan kami.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="umum">
            <div class="faq-question">
                <h3>Bagaimana jika produk yang saya gunakan tidak cocok?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Jika muncul gejala ketidakcocokan (iritasi, kemerahan, atau gatal), segera hentikan pemakaian produk tersebut.
                        Kamu bisa langsung berkonsultasi dengan admin kami via WhatsApp untuk mendapatkan saran perawatan alternatif atau rekomendasi produk lain yang lebih sesuai dengan tipe kulit sensitifmu.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="order">
            <div class="faq-question">
                <h3>Apakah bisa melakukan pengembalian barang (retur)?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Pengembalian barang dapat diajukan jika produk yang diterima dalam kondisi rusak, cacat produksi, atau salah kirim.
                        Syarat wajib pengajuan retur adalah menyertakan **video unboxing paket lengkap dari awal membuka tanpa jeda/editing** dan menghubungi admin maksimal 2x24 jam sejak barang diterima.
                    </p>
                </div>
            </div>
        </div>

        <div class="faq-item card" data-category="shipping">
            <div class="faq-question">
                <h3>Bagaimana cara melacak status paket pengiriman?</h3>
                <span class="faq-icon-indicator"><i class="fa-solid fa-chevron-down"></i></span>
            </div>
            <div class="faq-answer">
                <div class="faq-answer-content">
                    <p>
                        Setelah pesanan dikirim oleh kurir, nomor resi pengiriman (AWB) akan di-update di halaman akun (My Orders) atau invoice digital kamu.
                        Kamu bisa langsung menyalin nomor resi tersebut dan melakukan tracking di website resmi kurir ekspedisi yang bersangkutan.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- No FAQ Search Results -->
    <div id="faqNoResults" class="faq-no-results card" style="display: none;">
        <i class="fa-regular fa-face-frown no-results-icon"></i>
        <h3>Pertanyaan tidak ditemukan</h3>
        <p>Maaf, kami tidak dapat menemukan jawaban untuk kata kunci tersebut. Coba gunakan kata kunci lain atau langsung hubungi admin.</p>
    </div>

    <div class="contact-bottom card">
        <div class="contact-bottom-info">
            <h2>Masih ada pertanyaan lain?</h2>
            <p>Admin kami siap membantu menjawab pertanyaan atau konsultasi kulitmu secara gratis.</p>
        </div>

        <a class="btn contact-wa-btn" href="<?= e($whatsappUrl) ?>" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp"></i>
            Tanya Sekarang
        </a>
    </div>
</section>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
