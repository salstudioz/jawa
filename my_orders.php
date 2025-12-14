<?php
require_once 'includes/db_config.php';
$page_title = 'Pesanan Saya - Jawa Travel';

// Cek login
if (!isset($_SESSION['user_id'])) {
    header('Location: masuk.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil pesanan user
$sql = "SELECT p.*, pk.nama_paket, pk.gambar_url 
        FROM pemesanan p
        LEFT JOIN paket_wisata pk ON p.id_paket = pk.id_paket
        WHERE p.id_user = ?
        ORDER BY p.tanggal_pemesanan DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-header" style="background-color: var(--primary-blue); min-height: 60vh;">
    <div style="text-align: center; color: white; padding-top: 100px;">
        <h1 data-aos="fade-down">Pesanan Saya</h1>
        <p data-aos="fade-up">Riwayat pemesanan Anda di Jawa Travel</p>
    </div>
</div>

<div class="my-orders-container" style="padding: 50px 80px; background: #f9f9f9; min-height: 60vh;">
    <?php if ($result->num_rows > 0): ?>
        <div class="orders-grid">
            <?php while ($row = $result->fetch_assoc()):
                $status_color = '';
                if ($row['status_pemesanan'] == 'Lunas') $status_color = '#00FF44';
                elseif ($row['status_pemesanan'] == 'Pending') $status_color = 'orange';
                else $status_color = 'red';
            ?>
                <div class="order-card" style="background: white; border-radius: 10px; padding: 25px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <div style="display: grid; grid-template-columns: 100px 1fr auto; gap: 20px; align-items: center;">
                        <div>
                            <img src="<?php echo htmlspecialchars($row['gambar_url'] ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'); ?>"
                                alt="Paket" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px;">
                        </div>
                        <div>
                            <h3 style="color: #00AAFF; margin-bottom: 10px;">
                                <?php echo $row['is_custom'] ? 'Paket Kustom' : htmlspecialchars($row['nama_paket']); ?>
                            </h3>
                            <div style="display: flex; gap: 20px; margin-bottom: 10px; font-size: 14px; color: #666;">
                                <div><strong>ID:</strong> #<?php echo str_pad($row['id_pemesanan'], 6, '0', STR_PAD_LEFT); ?></div>
                                <div><strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($row['tanggal_pemesanan'])); ?></div>
                                <div><strong>Mulai:</strong> <?php echo date('d M Y', strtotime($row['tanggal_mulai'])); ?></div>
                                <div><strong>Jumlah:</strong> <?php echo $row['jumlah_orang']; ?> orang</div>
                            </div>
                            <?php if ($row['catatan_khusus']): ?>
                                <p style="font-size: 13px; color: #888; margin-top: 5px;">
                                    <strong>Catatan:</strong> <?php echo htmlspecialchars($row['catatan_khusus']); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 20px; font-weight: bold; color: #00AAFF; margin-bottom: 10px;">
                                Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?>
                            </div>
                            <div style="padding: 5px 15px; background: <?php echo $status_color; ?>20; color: <?php echo $status_color; ?>; border-radius: 20px; font-weight: bold; display: inline-block;">
                                <?php echo $row['status_pemesanan']; ?>
                            </div>
                            <div style="margin-top: 10px;">
                                <a href="print_invoice.php?id=<?php echo $row['id_pemesanan']; ?>" target="_blank"
                                    style="color: #00AAFF; text-decoration: none; font-size: 14px;">
                                    <i class="fas fa-print"></i> Invoice
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div style="text-align: center; padding: 50px;">
            <i class="fas fa-shopping-cart" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
            <h3 style="color: #666; margin-bottom: 15px;">Belum Ada Pesanan</h3>
            <p style="color: #888; margin-bottom: 30px;">Anda belum melakukan pemesanan apapun.</p>
            <a href="index.php#paket" class="btn" style="background-color: #00AAFF; color: white; padding: 10px 30px;">Lihat Paket Wisata</a>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>