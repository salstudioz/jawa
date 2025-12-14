<?php
require_once 'includes/db_config.php';
$page_title = 'Hasil Pencarian - Jawa Travel';

// Ambil parameter pencarian
$provinsi_id = isset($_GET['provinsi']) ? intval($_GET['provinsi']) : 0;
$destinasi_id = isset($_GET['destinasi']) ? intval($_GET['destinasi']) : 0;
$hotel_id = isset($_GET['hotel']) ? intval($_GET['hotel']) : 0;

// Query untuk mendapatkan data berdasarkan parameter
$sql = "SELECT * FROM paket_wisata WHERE 1=1";
$params = [];
$types = "";

if ($provinsi_id > 0) {
    $sql .= " AND id_provinsi = ?";
    $params[] = $provinsi_id;
    $types .= "i";
}

if ($destinasi_id > 0) {
    $sql .= " AND id_paket IN (SELECT id_paket FROM detail_paket_destinasi WHERE id_destinasi = ?)";
    $params[] = $destinasi_id;
    $types .= "i";
}

$sql .= " ORDER BY harga_total ASC";

$stmt = $conn->prepare($sql);
if ($params) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Ambil data provinsi, destinasi, hotel untuk ditampilkan
$provinsi = $provinsi_id ? $conn->query("SELECT nama_provinsi FROM provinsi WHERE id_provinsi = $provinsi_id")->fetch_assoc() : null;
$destinasi = $destinasi_id ? $conn->query("SELECT nama_destinasi FROM destinasi WHERE id_destinasi = $destinasi_id")->fetch_assoc() : null;
$hotel = $hotel_id ? $conn->query("SELECT nama_hotel, bintang FROM hotel WHERE id_hotel = $hotel_id")->fetch_assoc() : null;
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-header" style="background-image: url('https://images.unsplash.com/photo-1544551763-46a013bb70d5?q=80&w=1920&auto=format&fit=crop'); min-height: 60vh;">
    <div style="text-align: center; color: white; padding-top: 100px;">
        <h1 data-aos="fade-down">Hasil Pencarian Paket</h1>
        <p data-aos="fade-up">Berikut adalah paket wisata yang sesuai dengan pilihan Anda</p>
    </div>
</div>

<div class="search-results" style="padding: 50px 80px; background: #f9f9f9;">
    <!-- Filter Summary -->
    <div class="filter-summary" style="background: white; padding: 20px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
        <h3 style="color: #00AAFF; margin-bottom: 15px;">Filter Pencarian Anda:</h3>
        <div style="display: flex; gap: 30px; flex-wrap: wrap;">
            <?php if ($provinsi): ?>
                <div>
                    <strong>Provinsi:</strong> <?php echo htmlspecialchars($provinsi['nama_provinsi']); ?>
                </div>
            <?php endif; ?>
            <?php if ($destinasi): ?>
                <div>
                    <strong>Destinasi:</strong> <?php echo htmlspecialchars($destinasi['nama_destinasi']); ?>
                </div>
            <?php endif; ?>
            <?php if ($hotel): ?>
                <div>
                    <strong>Hotel:</strong> <?php echo htmlspecialchars($hotel['nama_hotel']); ?> (Bintang: <?php echo $hotel['bintang']; ?>)
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Results Grid -->
    <div class="packages-grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <?php
                // Ambil data provinsi untuk paket
                $paket_provinsi = $conn->query("SELECT nama_provinsi FROM provinsi WHERE id_provinsi = " . $row['id_provinsi'])->fetch_assoc();
                ?>
                <div class="package-card" data-aos="fade-up">
                    <img src="<?php echo htmlspecialchars($row['gambar_url']); ?>" alt="<?php echo htmlspecialchars($row['nama_paket']); ?>" class="package-img">
                    <div class="package-info">
                        <h3 class="package-title"><?php echo htmlspecialchars($row['nama_paket']); ?></h3>
                        <div class="package-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($paket_provinsi['nama_provinsi']); ?></div>
                        <div class="rating">
                            <?php
                            $rating = $row['rating'] ?? 4.0;
                            $full_stars = floor($rating);
                            $has_half = $rating - $full_stars >= 0.5;

                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $full_stars):
                                    echo '<i class="fas fa-star"></i>';
                                elseif ($i == $full_stars + 1 && $has_half):
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                else:
                                    echo '<i class="far fa-star"></i>';
                                endif;
                            endfor;
                            ?>
                            <span style="color: #666; font-size: 12px; margin-left: 5px;"><?php echo number_format($rating, 1); ?></span>
                        </div>
                        <p style="font-size: 14px; color: #666; margin: 10px 0; line-height: 1.5;">
                            <?php echo substr(htmlspecialchars($row['deskripsi']), 0, 100); ?>...
                        </p>
                        <div class="price-row">
                            <div class="price">Rp <?php echo number_format($row['harga_total'], 0, ',', '.'); ?> <span>/Pax</span></div>
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <a href="orders.php?paket=<?php echo $row['id_paket']; ?>&provinsi=<?php echo $provinsi_id; ?>&destinasi=<?php echo $destinasi_id; ?>&hotel=<?php echo $hotel_id; ?>" class="order-btn">Pesan Sekarang</a>
                            <?php else: ?>
                                <a href="masuk.php" class="order-btn">Login untuk Pesan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="grid-column: 1/-1; text-align: center; padding: 50px;">
                <i class="fas fa-search" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                <h3 style="color: #666; margin-bottom: 15px;">Tidak Ada Paket Ditemukan</h3>
                <p style="color: #888; margin-bottom: 30px;">Maaf, tidak ada paket yang sesuai dengan filter pencarian Anda.</p>
                <a href="index.php#kustom" class="btn" style="background-color: #00AAFF; color: white; padding: 10px 30px;">Ulangi Pencarian</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Custom Package Option -->
    <?php if ($provinsi_id || $destinasi_id || $hotel_id): ?>
        <div class="custom-package-option" style="background: white; padding: 30px; border-radius: 10px; margin-top: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="color: #00AAFF; margin-bottom: 15px;">Ingin Paket Kustom?</h3>
            <p style="color: #666; margin-bottom: 20px;">Kami dapat membuatkan paket khusus untuk Anda berdasarkan pilihan yang telah Anda tentukan.</p>
            <a href="orders.php?custom=1&provinsi=<?php echo $provinsi_id; ?>&destinasi=<?php echo $destinasi_id; ?>&hotel=<?php echo $hotel_id; ?>" class="btn" style="background-color: #00AAFF; color: white; padding: 12px 30px; font-weight: 600;">
                <i class="fas fa-magic" style="margin-right: 10px;"></i> Buat Paket Kustom
            </a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>