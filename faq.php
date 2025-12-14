<?php
require_once 'includes/db_config.php';
$page_title = 'FAQ - Jawa Travel';
require_once 'includes/header.php';
?>

<div class="page-header" style="background: #fff; display: block; padding-top: 150px; min-height: auto;">
    <h1 class="faq-header" data-aos="fade-down">Pertanyaan yang sering ditanyakan</h1>

    <div class="faq-container">
        <div class="faq-item" data-aos="fade-up">
            <div class="faq-question">
                Bagaimana cara memesan paket wisata?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="faq-label">Jawaban:</span>
                Anda dapat memesan paket wisata dengan mendaftar akun terlebih dahulu, kemudian pilih paket yang diinginkan dan klik tombol "Order". Ikuti proses pembayaran yang tersedia.
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="100">
            <div class="faq-question">
                Bagaimana cara membatalkan pesanan?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="faq-label">Jawaban:</span>
                Anda dapat menghubungi customer service kami melalui halaman Kontak atau telepon langsung di 1800-400-930 minimal 3 hari sebelum tanggal keberangkatan.
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="200">
            <div class="faq-question">
                Apakah ada biaya tambahan tersembunyi?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="faq-label">Jawaban:</span>
                Tidak, semua biaya sudah transparan sejak awal pemesanan. Harga yang tertera sudah termasuk akomodasi, transportasi, dan pemandu wisata sesuai paket.
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="300">
            <div class="faq-question">
                Metode pembayaran apa saja yang tersedia?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="faq-label">Jawaban:</span>
                Kami menerima Transfer Bank (BCA, Mandiri, BRI), Kartu Kredit (Visa, Mastercard), dan E-Wallet (GoPay, OVO, Dana).
            </div>
        </div>

        <div class="faq-item" data-aos="fade-up" data-aos-delay="400">
            <div class="faq-question">
                Apakah bisa membuat paket kustom?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                <span class="faq-label">Jawaban:</span>
                Ya, Anda dapat membuat paket kustom dengan memilih provinsi, destinasi, dan hotel yang diinginkan melalui fitur "Paket Kustom" di halaman utama.
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>