<?php
require_once 'includes/db_config.php';
$page_title = 'Jawa - Travel Agency';
require_once 'includes/header.php';
?>

<header class="hero">
    <div class="hero-card" data-aos="fade-up" data-aos-duration="1000">
        <h1>Rencanakan Perjalanan<br>Yang Tak Terlupakan</h1>
        <p>Akses eksklusif ke destinasi tersembunyi dan layanan terbaik, diatur oleh tim ahli perjalanan yang berdedikasi.</p>
        <a href="#paket" class="hero-btn">Rencanakan perjalanan sekarang <i class="fas fa-arrow-right"></i></a>
    </div>
</header>

<section class="features" data-aos="fade-up">
    <div style="text-align: center; margin-bottom: 40px; font-weight: 600; font-size: 32px;">Kenapa Kami Yang Terbaik?</div>

    <div class="features-grid">
        <div class="feature-item">
            <i class="fas fa-calendar-alt"></i>
            <h3>Perencanaan & Kustomisasi Rencana Perjalanan</h3>
            <p>Kami merancang seluruh rangkaian perjalanan Anda, mulai dari penerbangan, akomodasi, hingga kegiatan harian secara presisi dan personal.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-gem"></i>
            <h3>Akses Eksklusif & Pemesanan Premium</h3>
            <p>Memanfaatkan jaringan global kami untuk mengamankan akomodasi hotel bintang lima dan akses eksklusif yang sulit didapat publik.</p>
        </div>
        <div class="feature-item">
            <i class="fas fa-phone-alt"></i>
            <h3>Bantuan Perjalanan Real-Time</h3>
            <p>Menyediakan dukungan on-call 24/7. Kami siap siaga menangani perubahan tak terduga atau keadaan darurat dengan cepat.</p>
        </div>
    </div>
</section>
<section id="kustom" class="custom-package">
    <h2 class="section-title" data-aos="zoom-in">CARI PAKET</h2>

    <div class="search-container" data-aos="flip-up">
        <form method="GET" action="search.php" id="customForm" style="display: contents;">
            <div class="search-group">
                <label class="search-label">Pilih Provinsi</label>
                <div class="custom-select-wrapper">
                    <select name="provinsi" class="custom-select" required>
                        <option value="">Pilih Provinsi...</option>
                        <?php
                        $prov_sql = "SELECT * FROM provinsi ORDER BY nama_provinsi";
                        $prov_result = $conn->query($prov_sql);
                        while ($prov = $prov_result->fetch_assoc()) {
                            echo '<option value="' . $prov['id_provinsi'] . '">' . htmlspecialchars($prov['nama_provinsi']) . '</option>';
                        }
                        ?>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
            </div>
            <div class="search-group">
                <label class="search-label">Pilih Destinasi</label>
                <div class="custom-select-wrapper">
                    <select name="destinasi" class="custom-select" required>
                        <option value="">Pilih Destinasi...</option>
                        <?php
                        $dest_sql = "SELECT * FROM destinasi ORDER BY nama_destinasi";
                        $dest_result = $conn->query($dest_sql);
                        while ($dest = $dest_result->fetch_assoc()) {
                            echo '<option value="' . $dest['id_destinasi'] . '">' . htmlspecialchars($dest['nama_destinasi']) . '</option>';
                        }
                        ?>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
            </div>
            <div class="search-group">
                <label class="search-label">Pilih Hotel</label>
                <div class="custom-select-wrapper">
                    <select name="hotel" class="custom-select" required>
                        <option value="">Pilih Hotel...</option>
                        <?php
                        $hotel_sql = "SELECT * FROM hotel ORDER BY bintang DESC, nama_hotel";
                        $hotel_result = $conn->query($hotel_sql);
                        while ($hotel = $hotel_result->fetch_assoc()) {
                            echo '<option value="' . $hotel['id_hotel'] . '">' . htmlspecialchars($hotel['nama_hotel']) . ' (Bintang: ' . $hotel['bintang'] . ')</option>';
                        }
                        ?>
                    </select>
                    <i class="fas fa-chevron-down select-arrow"></i>
                </div>
            </div>
            <div class="search-group">
                <button type="submit" class="search-btn">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>

    <!-- Quick Custom Order Info -->
    <div style="text-align: center; margin-top: 40px; color: #666;">
        <p><i class="fas fa-info-circle" style="color: #00AAFF; margin-right: 5px;"></i>
            Tidak menemukan paket yang cocok? Buat paket kustom sesuai keinginan Anda!</p>
    </div>
</section>

<?php
// Ambil data paket dari database
$sql = "SELECT * FROM paket_wisata ORDER BY harga_total ASC LIMIT 6";
$result = $conn->query($sql);
?>

<section id="paket" class="packages">
    <h2 class="section-title" data-aos="zoom-in">PAKET PARIWISATA</h2>

    <div class="packages-grid">
        <?php
        $delay = 100;
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Ambil nama provinsi
                $prov_sql = "SELECT nama_provinsi FROM provinsi WHERE id_provinsi = ?";
                $prov_stmt = $conn->prepare($prov_sql);
                $prov_stmt->bind_param("i", $row['id_provinsi']);
                $prov_stmt->execute();
                $prov_result = $prov_stmt->get_result();
                $provinsi = $prov_result->fetch_assoc();

                // Format rating stars
                $rating = $row['rating'] ?? 4.0;
                $full_stars = floor($rating);
                $has_half = $rating - $full_stars >= 0.5;
        ?>
                <div class="package-card" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    <img src="<?php echo htmlspecialchars($row['gambar_url']); ?>" alt="<?php echo htmlspecialchars($row['nama_paket']); ?>" class="package-img">
                    <div class="package-info">
                        <h3 class="package-title"><?php echo htmlspecialchars($row['nama_paket']); ?></h3>
                        <div class="package-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($provinsi['nama_provinsi']); ?>
                        </div>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <?php if ($i <= $full_stars): ?>
                                    <i class="fas fa-star"></i>
                                <?php elseif ($i == $full_stars + 1 && $has_half): ?>
                                    <i class="fas fa-star-half-alt"></i>
                                <?php else: ?>
                                    <i class="far fa-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span style="color: #666; font-size: 12px; margin-left: 5px;">
                                <?php echo number_format($rating, 1); ?>
                            </span>
                        </div>
                        <p style="font-size: 14px; color: #666; margin: 10px 0; line-height: 1.5; height: 60px; overflow: hidden;">
                            <?php echo substr(htmlspecialchars($row['deskripsi']), 0, 100); ?>...
                        </p>
                        <div class="price-row">
                            <div class="price">Rp <?php echo number_format($row['harga_total'], 0, ',', '.'); ?> <span>/Pax</span></div>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="orders.php?paket=<?php echo $row['id_paket']; ?>" class="order-btn" onclick="return confirmOrder(<?php echo $row['id_paket']; ?>, '<?php echo htmlspecialchars(addslashes($row['nama_paket'])); ?>')">
                                    <i class="fas fa-shopping-cart" style="margin-right: 5px;"></i> Pesan
                                </a>
                            <?php else: ?>
                                <a href="masuk.php?redirect=orders.php?paket=<?php echo $row['id_paket']; ?>" class="order-btn">
                                    <i class="fas fa-shopping-cart" style="margin-right: 5px;"></i> Pesan
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
        <?php
                $delay += 100;
            }
        } else {
            echo '<p style="grid-column: 1/-1; text-align: center; padding: 40px;">Belum ada paket tersedia</p>';
        }
        ?>
    </div>

    <!-- View More Button -->
    <div style="text-align: center; margin-top: 50px;">
        <a href="#kustom" class="btn" style="background-color: #00AAFF; color: white; padding: 12px 40px; font-size: 16px;">
            <i class="fas fa-search" style="margin-right: 10px;"></i> Cari Paket Lainnya
        </a>
    </div>
</section>


<!-- Testimonials Section -->
<section class="testimonials" style="padding: 60px 80px; background: #f9f9f9;">
    <h2 class="section-title" data-aos="zoom-in">TESTIMONI PELANGGAN</h2>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px;">
        <div class="testimonial-card" data-aos="fade-up">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                <div>
                    <div style="font-weight: 600;">Budi Santoso</div>
                    <div style="color: #00AAFF; font-size: 14px;">Paket Bromo 3D2N</div>
                </div>
            </div>
            <div style="color: #FFD700; margin-bottom: 10px;">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p style="color: #666; font-style: italic;">"Pelayanan sangat memuaskan! Pemandu wisata profesional dan akomodasi sesuai ekspektasi."</p>
        </div>

        <div class="testimonial-card" data-aos="fade-up" data-aos-delay="100">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                <div>
                    <div style="font-weight: 600;">Sari Dewi</div>
                    <div style="color: #00AAFF; font-size: 14px;">Paket Jogja Heritage</div>
                </div>
            </div>
            <div style="color: #FFD700; margin-bottom: 10px;">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
            </div>
            <p style="color: #666; font-style: italic;">"Perjalanan sangat terorganisir. Semua tempat wisata dikunjungi tepat waktu tanpa terburu-buru."</p>
        </div>

        <div class="testimonial-card" data-aos="fade-up" data-aos-delay="200">
            <div style="display: flex; align-items: center; margin-bottom: 15px;">
                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="User" style="width: 50px; height: 50px; border-radius: 50%; margin-right: 15px;">
                <div>
                    <div style="font-weight: 600;">Agus Wijaya</div>
                    <div style="color: #00AAFF; font-size: 14px;">Paket Kustom</div>
                </div>
            </div>
            <div style="color: #FFD700; margin-bottom: 10px;">
                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>
            </div>
            <p style="color: #666; font-style: italic;">"Fleksibel dalam membuat itinerary sesuai keinginan. Tim responsif dalam menangani request."</p>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="cta" style="background: linear-gradient(135deg, #00AAFF, #0072AA); padding: 80px; text-align: center; color: white;">
    <h2 style="font-size: 36px; margin-bottom: 20px;" data-aos="fade-down">Siap Memulai Perjalanan?</h2>
    <p style="font-size: 18px; margin-bottom: 30px; max-width: 600px; margin-left: auto; margin-right: auto;" data-aos="fade-up">
        Bergabunglah dengan ribuan traveler yang telah mempercayakan perjalanan mereka kepada kami.
    </p>
    <div style="display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <a href="daftar.php" class="btn" style="background: white; color: #00AAFF; padding: 12px 30px; font-weight: 600;" data-aos="zoom-in">
                <i class="fas fa-user-plus" style="margin-right: 10px;"></i> Daftar Sekarang
            </a>
        <?php endif; ?>
        <a href="#paket" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 12px 30px; font-weight: 600;" data-aos="zoom-in" data-aos-delay="100">
            <i class="fas fa-suitcase" style="margin-right: 10px;"></i> Lihat Paket Wisata
        </a>
    </div>
</section>

<script>
    // Function untuk konfirmasi sebelum order
    function confirmOrder(paketId, paketNama) {
        if (confirm(`Anda akan memesan paket:\n${paketNama}\n\nLanjutkan ke halaman pemesanan?`)) {
            return true;
        }
        return false;
    }

    // Function untuk quick order dengan jumlah orang
    function quickOrder(paketId, paketNama) {
        const jumlahOrang = prompt(`Pesan paket: ${paketNama}\n\nMasukkan jumlah orang:`, "2");

        if (jumlahOrang && !isNaN(jumlahOrang) && jumlahOrang > 0) {
            window.location.href = `orders.php?paket=${paketId}&jumlah=${jumlahOrang}`;
        } else if (jumlahOrang !== null) {
            alert('Masukkan jumlah orang yang valid!');
        }
    }

    // Quick view package details
    function viewPackageDetails(paketId) {
        // Ini bisa diimplementasikan dengan modal atau halaman detail
        alert('Fitur detail paket akan segera hadir!');
    }
</script>

<?php
require_once 'includes/footer.php';
$conn->close();
?>