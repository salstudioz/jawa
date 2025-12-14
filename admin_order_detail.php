<?php
require_once 'includes/admin_header.php';
$page_title = 'Detail Pesanan - Jawa Travel';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil detail pesanan
$order_sql = "SELECT p.*, u.nama, u.email, u.nomor_telepon, u.alamat, pk.nama_paket, pk.deskripsi as deskripsi_paket
              FROM pemesanan p
              LEFT JOIN user u ON p.id_user = u.id_user
              LEFT JOIN paket_wisata pk ON p.id_paket = pk.id_paket
              WHERE p.id_pemesanan = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location.href='admin_pesanan.php';</script>";
    exit();
}

// Ambil destinasi untuk pesanan kustom
$destinasi_sql = "SELECT d.* FROM destinasi d
                  INNER JOIN destinasi_pemesanan dp ON d.id_destinasi = dp.id_destinasi
                  WHERE dp.id_pemesanan = ?";
$destinasi_stmt = $conn->prepare($destinasi_sql);
$destinasi_stmt->bind_param("i", $order_id);
$destinasi_stmt->execute();
$destinasi_result = $destinasi_stmt->get_result();

// Ambil detail pemesanan (untuk custom)
$detail_sql = "SELECT dp.*, pr.nama_provinsi, h.nama_hotel, h.bintang 
               FROM detail_pemesanan dp
               LEFT JOIN provinsi pr ON dp.id_provinsi = pr.id_provinsi
               LEFT JOIN hotel h ON dp.id_hotel = h.id_hotel
               WHERE dp.id_pemesanan = ?";
$detail_stmt = $conn->prepare($detail_sql);
$detail_stmt->bind_param("i", $order_id);
$detail_stmt->execute();
$detail_result = $detail_stmt->get_result();
$detail = $detail_result->fetch_assoc();
?>

<h1 class="page-title">Detail Pesanan #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></h1>

<div class="order-detail-container" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">

    <!-- Status Bar -->
    <div style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin-bottom: 30px; border-left: 4px solid #00AAFF;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="margin: 0; color: #333;">Status:
                    <span style="color: <?php
                                        echo $order['status_pemesanan'] == 'Lunas' ? 'green' : ($order['status_pemesanan'] == 'Pending' ? 'orange' : 'red');
                                        ?>; font-weight: bold;">
                        <?php echo $order['status_pemesanan']; ?>
                    </span>
                </h3>
                <p style="margin: 5px 0 0 0; color: #666;">
                    ID: #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?> |
                    Tanggal: <?php echo date('d M Y H:i', strtotime($order['tanggal_pemesanan'])); ?>
                </p>
            </div>
            <div style="display: flex; gap: 10px;">
                <a href="print_invoice.php?id=<?php echo $order_id; ?>" target="_blank"
                    class="btn-action btn-edit" style="background-color: #00AAFF;">
                    <i class="fas fa-print"></i> Invoice
                </a>
                <a href="admin_pesanan.php" class="btn-action" style="background-color: #666;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Informasi Utama -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Data Pemesan -->
        <div>
            <h3 style="color: #00AAFF; margin-bottom: 15px; border-bottom: 2px solid #00AAFF; padding-bottom: 5px;">
                <i class="fas fa-user"></i> Data Pemesan
            </h3>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['nama']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['nomor_telepon'] ?: '-'); ?></p>
                <?php if ($order['alamat']): ?>
                    <p><strong>Alamat:</strong> <?php echo htmlspecialchars($order['alamat']); ?></p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Detail Pesanan -->
        <div>
            <h3 style="color: #00AAFF; margin-bottom: 15px; border-bottom: 2px solid #00AAFF; padding-bottom: 5px;">
                <i class="fas fa-shopping-cart"></i> Detail Pesanan
            </h3>
            <div style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                <p><strong>Tanggal Mulai:</strong> <?php echo date('d M Y', strtotime($order['tanggal_mulai'])); ?></p>
                <p><strong>Jumlah Orang:</strong> <?php echo $order['jumlah_orang']; ?> orang</p>
                <p><strong>Jenis Paket:</strong> <?php echo $order['is_custom'] ? 'Paket Kustom' : 'Paket Reguler'; ?></p>
                <p><strong>Total Harga:</strong> <span style="font-size: 18px; font-weight: bold; color: #00AAFF;">
                        Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?>
                    </span></p>
                <?php if ($order['catatan_khusus']): ?>
                    <p><strong>Catatan Khusus:</strong> <?php echo htmlspecialchars($order['catatan_khusus']); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Informasi Paket -->
    <div style="margin-bottom: 30px;">
        <h3 style="color: #00AAFF; margin-bottom: 15px; border-bottom: 2px solid #00AAFF; padding-bottom: 5px;">
            <i class="fas fa-map-marked-alt"></i> Informasi Paket
        </h3>
        <div style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
            <?php if ($order['is_custom']): ?>
                <!-- Paket Kustom -->
                <p style="color: #00AAFF; font-weight: bold; margin-bottom: 15px;">
                    <i class="fas fa-star"></i> PAKET KUSTOM
                </p>

                <?php if ($destinasi_result->num_rows > 0): ?>
                    <p><strong>Destinasi:</strong></p>
                    <ul style="margin-left: 20px; margin-bottom: 15px;">
                        <?php while ($dest = $destinasi_result->fetch_assoc()): ?>
                            <li><?php echo htmlspecialchars($dest['nama_destinasi']); ?></li>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>

                <?php if ($detail): ?>
                    <?php if ($detail['nama_provinsi']): ?>
                        <p><strong>Provinsi:</strong> <?php echo htmlspecialchars($detail['nama_provinsi']); ?></p>
                    <?php endif; ?>
                    <?php if ($detail['nama_hotel']): ?>
                        <p><strong>Hotel:</strong> <?php echo htmlspecialchars($detail['nama_hotel']); ?>
                            (Bintang: <?php echo $detail['bintang']; ?>)
                        </p>
                    <?php endif; ?>
                    <?php if ($detail['jumlah_malam']): ?>
                        <p><strong>Lama Menginap:</strong> <?php echo $detail['jumlah_malam']; ?> malam</p>
                    <?php endif; ?>
                <?php endif; ?>
            <?php else: ?>
                <!-- Paket Reguler -->
                <p><strong>Nama Paket:</strong> <?php echo htmlspecialchars($order['nama_paket']); ?></p>
                <p><strong>Deskripsi:</strong></p>
                <div style="background: white; padding: 15px; border-radius: 5px; margin-top: 10px;">
                    <?php echo nl2br(htmlspecialchars($order['deskripsi_paket'])); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Aksi Admin -->
    <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee;">
        <h3 style="color: #00AAFF; margin-bottom: 15px;">Aksi Admin</h3>
        <div style="display: flex; gap: 15px; flex-wrap: wrap;">
            <!-- Status Update -->
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Update Status</label>
                <div style="display: flex; gap: 10px;">
                    <a href="?update_status&id=<?php echo $order_id; ?>&status=Lunas"
                        class="btn-action" style="background-color: #00FF44; <?php echo $order['status_pemesanan'] == 'Lunas' ? 'opacity: 0.7;' : ''; ?>">
                        <i class="fas fa-check"></i> Tandai Lunas
                    </a>
                    <a href="?update_status&id=<?php echo $order_id; ?>&status=Pending"
                        class="btn-action" style="background-color: orange; <?php echo $order['status_pemesanan'] == 'Pending' ? 'opacity: 0.7;' : ''; ?>">
                        <i class="fas fa-clock"></i> Tandai Pending
                    </a>
                    <a href="?update_status&id=<?php echo $order_id; ?>&status=Dibatalkan"
                        class="btn-action" style="background-color: #FF0000; <?php echo $order['status_pemesanan'] == 'Dibatalkan' ? 'opacity: 0.7;' : ''; ?>">
                        <i class="fas fa-times"></i> Batalkan
                    </a>
                </div>
            </div>

            <!-- Kontak Pemesan -->
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Kontak Pemesan</label>
                <div style="display: flex; gap: 10px;">
                    <?php if ($order['nomor_telepon']): ?>
                        <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $order['nomor_telepon']); ?>"
                            target="_blank" class="btn-action" style="background-color: #25D366;">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>
                    <a href="mailto:<?php echo htmlspecialchars($order['email']); ?>"
                        class="btn-action" style="background-color: #666;">
                        <i class="fas fa-envelope"></i> Email
                    </a>
                </div>
            </div>
        </div>

        <!-- Hapus Pesanan -->
        <div style="margin-top: 20px;">
            <button onclick="hapusPesanan(<?php echo $order_id; ?>)"
                class="btn-action btn-delete" style="width: 100%;">
                <i class="fas fa-trash"></i> Hapus Pesanan Ini
            </button>
        </div>
    </div>
</div>

<script>
    function hapusPesanan(id) {
        if (confirm('⚠️ PERINGATAN: Anda akan menghapus pesanan #' + id + '\n\nData yang dihapus tidak dapat dikembalikan!\n\nLanjutkan?')) {
            window.location.href = 'admin_pesanan.php?hapus=' + id;
        }
    }

    // Quick print
    function printInvoice() {
        window.open('print_invoice.php?id=<?php echo $order_id; ?>', '_blank');
    }
</script>

</main>
</body>

</html>