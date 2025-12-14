<?php
require_once 'includes/db_config.php';
$page_title = 'Tentang Kami - Jawa Travel';
require_once 'includes/header.php';

// Hitung statistik (Pindahkan logic ke atas agar rapi)
$stats = array();
$stats['pelanggan'] = $conn->query("SELECT COUNT(*) as total FROM pemesanan")->fetch_assoc()['total'];
$stats['paket'] = $conn->query("SELECT COUNT(*) as total FROM paket_wisata")->fetch_assoc()['total'];
$stats['mitra'] = $conn->query("SELECT COUNT(*) as total FROM (SELECT COUNT(DISTINCT id_hotel) FROM hotel UNION SELECT COUNT(DISTINCT id_guide) FROM tour_guide) as t")->fetch_assoc()['total'];
?>

<section class="section-komitmen">
    <div class="container-width">
        <div class="about-hero" data-aos="fade-down">
            <h1>Rencanakan Perjalanan<br>Yang Tak Terlupakan</h1>
        </div>

        <div class="glass-card" data-aos="fade-up">
            <div class="card-text">
                <h3>Komitmen Pelayanan</h3>
                <p>"Kami berkomitmen untuk memberikan pengalaman perjalanan terbaik dengan layanan yang personal dan profesional. Setiap perjalanan dirancang khusus untuk memenuhi kebutuhan dan keinginan Anda, dengan perhatian pada detail yang membuat perbedaan."</p>
            </div>
            <div class="card-image-wrapper">
                <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?q=80&w=500" alt="Komitmen">
            </div>
        </div>
    </div>
</section>

<section class="section-stats">
    <div class="container-width">
        <div class="stats-header">
            <h2>Hasil Pelayanan Kami</h2>
        </div>
        <div class="stats-grid" data-aos="zoom-in">
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['pelanggan']; ?>k+</div>
                <div class="stat-label">Pelanggan dilayani</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">98%</div>
                <div class="stat-label">Pelanggan merasa puas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number"><?php echo $stats['mitra']; ?>+</div>
                <div class="stat-label">Mitra pariwisata terdaftar</div>
            </div>
        </div>
    </div>
</section>

<section class="section-layanan ">
    <div class="container-width">
        <div class="glass-card reverse-layout" data-aos="fade-up">
            <div class="card-image-wrapper">
                <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?q=80&w=500" alt="Layanan">
            </div>
            <div class="card-text">
                <h3>Jangkauan Layanan</h3>
                <p>"Kami melayani seluruh wilayah Jawa dengan jaringan mitra yang luas. Dari Jawa Barat hingga Jawa Timur, kami menyediakan akses ke destinasi terbaik, akomodasi berkualitas, dan pemandu wisata profesional untuk memastikan perjalanan Anda tak terlupakan."</p>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>