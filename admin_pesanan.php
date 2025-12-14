<?php
require_once 'includes/admin_header.php';
$page_title = 'Pesanan Masuk - Jawa Travel';

// Update status pesanan
if (isset($_GET['update_status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];
    $conn->query("UPDATE pemesanan SET status_pemesanan = '$status' WHERE id_pemesanan = $id");
    header('Location: admin_pesanan.php?updated=1');
    exit();
}

// Hapus pesanan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Hapus data terkait terlebih dahulu
    $conn->query("DELETE FROM destinasi_pemesanan WHERE id_pemesanan = $id");
    $conn->query("DELETE FROM detail_pemesanan WHERE id_pemesanan = $id");
    $conn->query("DELETE FROM pemesanan WHERE id_pemesanan = $id");

    header('Location: admin_pesanan.php?deleted=1');
    exit();
}

// Filter status
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

// Query berdasarkan filter
$sql = "SELECT p.*, u.nama as nama_pemesan, u.email, pk.nama_paket 
        FROM pemesanan p 
        LEFT JOIN user u ON p.id_user = u.id_user 
        LEFT JOIN paket_wisata pk ON p.id_paket = pk.id_paket 
        WHERE 1=1";

if ($status_filter != 'all') {
    $sql .= " AND p.status_pemesanan = '$status_filter'";
}

$sql .= " ORDER BY p.tanggal_pemesanan DESC";

$pesanan_result = $conn->query($sql);
?>

<h1 class="page-title">Daftar Pesanan Masuk</h1>

<?php if (isset($_GET['updated'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
        Status pesanan berhasil diperbarui!
    </div>
<?php endif; ?>

<?php if (isset($_GET['deleted'])): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
        Pesanan berhasil dihapus!
    </div>
<?php endif; ?>

<div class="action-bar" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <div style="display: flex; gap: 10px;">
        <a href="admin_pesanan.php?status=all" class="btn-add" style="background: <?php echo $status_filter == 'all' ? '#0072AA' : '#00AAFF'; ?>;">Semua</a>
        <a href="admin_pesanan.php?status=Pending" class="btn-add" style="background: <?php echo $status_filter == 'Pending' ? '#0072AA' : '#00AAFF'; ?>;">Pending</a>
        <a href="admin_pesanan.php?status=Lunas" class="btn-add" style="background: <?php echo $status_filter == 'Lunas' ? '#0072AA' : '#00AAFF'; ?>;">Lunas</a>
        <a href="admin_pesanan.php?status=Dibatalkan" class="btn-add" style="background: <?php echo $status_filter == 'Dibatalkan' ? '#0072AA' : '#00AAFF'; ?>;">Dibatalkan</a>
    </div>

    <div>
        Total Pesanan: <strong><?php echo $pesanan_result->num_rows; ?></strong>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th>Nama Pemesan</th>
                <th>Paket Wisata</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th style="width: 250px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($pesanan_result->num_rows > 0): ?>
                <?php while ($row = $pesanan_result->fetch_assoc()):
                    $status_color = '';
                    if ($row['status_pemesanan'] == 'Lunas') $status_color = 'var(--success-green)';
                    elseif ($row['status_pemesanan'] == 'Pending') $status_color = 'orange';
                    elseif ($row['status_pemesanan'] == 'Dibatalkan') $status_color = 'red';
                ?>
                    <tr>
                        <td>#<?php echo str_pad($row['id_pemesanan'], 6, '0', STR_PAD_LEFT); ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($row['nama_pemesan']); ?></strong><br>
                            <small style="color: #666;"><?php echo htmlspecialchars($row['email']); ?></small><br>
                            <small style="color: #888;"><?php echo $row['jumlah_orang']; ?> orang</small>
                        </td>
                        <td>
                            <?php if ($row['is_custom']): ?>
                                <span style="color: #00AAFF;">Paket Kustom</span>
                            <?php else: ?>
                                <?php echo htmlspecialchars($row['nama_paket'] ?: '-'); ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo date('d M Y', strtotime($row['tanggal_pemesanan'])); ?><br>
                            <small style="color: #666;">Mulai: <?php echo date('d M Y', strtotime($row['tanggal_mulai'])); ?></small>
                        </td>
                        <td>Rp <?php echo number_format($row['total_harga'], 0, ',', '.'); ?></td>
                        <td style="color: <?php echo $status_color; ?>; font-weight: bold;"><?php echo $row['status_pemesanan']; ?></td>
                        <td>
                            <a href="admin_order_detail.php?id=<?php echo $row['id_pemesanan']; ?>" class="btn-action btn-edit" style="background-color: var(--primary-blue);">Detail</a>

                            <select onchange="updateStatus(<?php echo $row['id_pemesanan']; ?>, this.value)"
                                style="padding: 5px; border-radius: 5px; border: 1px solid #ddd; font-size: 12px; margin-top: 5px; width: 120px;">
                                <option value="Pending" <?php echo ($row['status_pemesanan'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Lunas" <?php echo ($row['status_pemesanan'] == 'Lunas') ? 'selected' : ''; ?>>Lunas</option>
                                <option value="Dibatalkan" <?php echo ($row['status_pemesanan'] == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>

                            <button class="btn-action btn-delete" onclick="hapusPesanan(<?php echo $row['id_pemesanan']; ?>)" style="margin-top: 5px; width: 120px;">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">
                        <i class="fas fa-inbox" style="font-size: 40px; color: #ddd; margin-bottom: 10px;"></i><br>
                        Tidak ada pesanan dengan status "<?php echo $status_filter; ?>"
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
    function updateStatus(id, status) {
        if (confirm('Ubah status pesanan #' + id + ' menjadi "' + status + '"?')) {
            window.location.href = '?update_status&id=' + id + '&status=' + status;
        }
    }

    function hapusPesanan(id) {
        if (confirm('Apakah Anda yakin ingin menghapus pesanan #' + id + '?\n\nData yang dihapus tidak dapat dikembalikan!')) {
            window.location.href = '?hapus=' + id;
        }
    }

    // Quick status update buttons
    function quickUpdate(id, status) {
        if (confirm('Ubah status pesanan menjadi "' + status + '"?')) {
            window.location.href = '?update_status&id=' + id + '&status=' + status;
        }
    }
</script>

</main>
</body>

</html>