<?php
$title = 'Blog';
require BASE_PATH . '/views/layouts/header.php';

// 1. Tambahkan key 'content' untuk mengisi teks panjang di dalam popup
$posts = [
    [
        'category' => 'Skincare Tips',
        'title' => '5 Langkah Skincare Routine untuk Kulit Sehat',
        'excerpt' => 'Rutinitas sederhana yang bisa bantu kulit tetap lembap, bersih, dan glowing setiap hari.',
        'content' => '<strong>Langkah-langkah:</strong><br>1. Cleansing: Bersihkan wajah dari kotoran.<br>2. Toning: Kembalikan pH kulit.<br>3. Serum: Berikan nutrisi ekstra.<br>4. Moisturizer: Kunci kelembapan.<br>5. Sunscreen: Lindungi dari sinar UV.',
        'image' => BASE_URL . '/assets/img/1.jpg',
        'date' => '13 April 2026',
        'author' => 'Beauty Care Team',
    ],
    [
        'category' => 'Ingredients',
        'title' => 'Kenapa Niacinamide Bagus untuk Wajah?',
        'excerpt' => 'Niacinamide membantu menjaga skin barrier, mengontrol minyak, dan membuat warna kulit tampak lebih merata.',
        'content' => '<strong>Manfaat Niacinamide:</strong><br>1. Memperkuat skin barrier.<br>2. Mengurangi produksi sebum berlebih.<br>3. Memudarkan bekas jerawat (hiperpigmentasi).',
        'image' => BASE_URL . '/assets/img/1.jpg',
        'date' => '10 April 2026',
        'author' => 'Beauty Care Team',
    ],
    [
        'category' => 'Beauty Guide',
        'title' => 'Cara Memilih Serum Sesuai Jenis Kulit',
        'excerpt' => 'Kenali kebutuhan kulitmu dulu sebelum memilih serum agar hasil perawatan lebih maksimal.',
        'content' => '<strong>Panduan Memilih:</strong><br>1. Kulit Kering: Cari kandungan Hyaluronic Acid.<br>2. Kulit Berminyak: Pilih Niacinamide atau Salicylic Acid.<br>3. Kulit Kusam: Gunakan Vitamin C.',
        'image' => BASE_URL . '/assets/img/1.jpg',
        'date' => '8 April 2026',
        'author' => 'Beauty Care Team',
    ],
];
?>

<style>
    /* CSS Tambahan untuk Popup (Modal) */
    .modal-overlay {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        overflow-y: auto;
    }

    .modal-content-box {
        background-color: #fff;
        margin: 5% auto;
        padding: 20px 30px;
        border-radius: 8px;
        width: 80%;
        max-width: 800px;
        position: relative;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .modal-close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 24px;
        font-weight: bold;
        color: #aaa;
        cursor: pointer;
        border: 1px solid #ddd;
        padding: 0 8px;
        border-radius: 4px;
    }

    .modal-close:hover {
        color: #333;
        border-color: #333;
    }

    .modal-header h2 {
        margin-top: 0;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        font-size: 24px;
    }

    .modal-body {
        display: flex;
        gap: 20px;
        margin-top: 20px;
    }

    .modal-image-container img {
        max-width: 300px;
        border-radius: 8px;
        object-fit: cover;
    }

    .modal-text {
        flex: 1;
        font-size: 15px;
        line-height: 1.6;
    }

    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .modal-body {
            flex-direction: column;
        }
        .modal-image-container img {
            max-width: 100%;
        }
    }
</style>

<section class="blog-page">
    <div class="section-intro">
        <span class="hero-badge">Beauty Blog</span>
        <h1>Tips, Insight & Skincare Articles</h1>
        <p>
            Temukan berbagai artikel seputar perawatan kulit, tips kecantikan,
            dan rekomendasi produk untuk rutinitas harianmu.
        </p>
    </div>

    <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
            <article class="card blog-card">
                <img class="blog-thumb" src="<?= e($post['image']) ?>" alt="<?= e($post['title']) ?>">

                <div class="blog-content">
                    <span class="blog-category"><?= e($post['category']) ?></span>
                    <h3><?= e($post['title']) ?></h3>
                    <p class="blog-meta"><?= e($post['date']) ?> • <?= e($post['author']) ?></p>
                    <p><?= e($post['excerpt']) ?></p>

                    <!-- 2. Tambahkan class 'open-modal-btn' dan data attributes -->
                    <a class="btn btn-outline blog-btn open-modal-btn" href="#" 
                       data-title="<?= e($post['title']) ?>"
                       data-image="<?= e($post['image']) ?>"
                       data-content="<?= e($post['content']) ?>">
                        Read More
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</section>

<!-- 3. Struktur HTML Popup Modal (Disembunyikan secara default) -->
<div id="blogModal" class="modal-overlay">
    <div class="modal-content-box">
        <span class="modal-close" id="closeModal">&times;</span>
        <div class="modal-header">
            <h2 id="modalTitle">Judul Artikel</h2>
        </div>
        <div class="modal-body">
            <div class="modal-image-container">
                <img id="modalImage" src="" alt="Blog Image">
            </div>
            <div class="modal-text" id="modalText">
                <!-- Konten akan dimuat lewat JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript untuk mengontrol Popup
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('blogModal');
        const closeModalBtn = document.getElementById('closeModal');
        const openModalBtns = document.querySelectorAll('.open-modal-btn');

        // Fungsi saat tombol Read More diklik
        openModalBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah halaman scroll ke atas

                // Ambil data dari atribut tombol
                const title = this.getAttribute('data-title');
                const image = this.getAttribute('data-image');
                const content = this.getAttribute('data-content');

                // Masukkan data ke dalam modal
                document.getElementById('modalTitle').innerText = title;
                document.getElementById('modalImage').src = image;
                document.getElementById('modalText').innerHTML = content;

                // Tampilkan modal
                modal.style.display = 'block';
            });
        });

        // Fungsi untuk menutup modal saat klik tombol X
        closeModalBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        // Fungsi untuk menutup modal saat klik di luar area box putih
        window.addEventListener('click', function(e) {
            if (e.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>

<?php require BASE_PATH . '/views/layouts/footer.php'; ?>
