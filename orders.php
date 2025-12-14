<?php
require_once 'includes/db_config.php';
$page_title = 'Pemesanan - Jawa Travel';

// Cek login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
    header('Location: masuk.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$paket_id = isset($_GET['paket']) ? intval($_GET['paket']) : 0;
$is_custom = isset($_GET['custom']) ? intval($_GET['custom']) : 0;
$provinsi_id = isset($_GET['provinsi']) ? intval($_GET['provinsi']) : 0;
$destinasi_id = isset($_GET['destinasi']) ? intval($_GET['destinasi']) : 0;
$hotel_id = isset($_GET['hotel']) ? intval($_GET['hotel']) : 0;

// Ambil data user
$user_sql = "SELECT * FROM user WHERE id_user = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user = $user_result->fetch_assoc();

$paket = null;
$total_harga = 0;
$paket_nama = "Paket Kustom";

if ($paket_id > 0 && !$is_custom) {
    // Ambil data paket wisata
    $paket_sql = "SELECT p.*, pr.nama_provinsi FROM paket_wisata p 
                  LEFT JOIN provinsi pr ON p.id_provinsi = pr.id_provinsi 
                  WHERE p.id_paket = ?";
    $paket_stmt = $conn->prepare($paket_sql);
    $paket_stmt->bind_param("i", $paket_id);
    $paket_stmt->execute();
    $paket_result = $paket_stmt->get_result();
    $paket = $paket_result->fetch_assoc();

    if ($paket) {
        $paket_nama = $paket['nama_paket'];
        $total_harga = $paket['harga_total'];
    }
}

// Proses pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $jumlah_orang = intval($_POST['jumlah_orang']);
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $catatan_khusus = $_POST['catatan_khusus'] ?? '';
    $metode_pembayaran = $_POST['metode_pembayaran'];

    // Hitung total harga
    if ($is_custom) {
        // Hitung harga paket kustom
        $total_harga = 0;

        if ($hotel_id > 0) {
            $hotel_sql = "SELECT harga_per_malam FROM hotel WHERE id_hotel = ?";
            $hotel_stmt = $conn->prepare($hotel_sql);
            $hotel_stmt->bind_param("i", $hotel_id);
            $hotel_stmt->execute();
            $hotel_result = $hotel_stmt->get_result();
            $hotel_data = $hotel_result->fetch_assoc();

            $total_harga += $hotel_data['harga_per_malam'] * 3 * $jumlah_orang; // 3 malam
        }

        // Tambahkan biaya transport dan guide
        $total_harga += 500000 * $jumlah_orang; // Transport
        $total_harga += 300000 * 3; // Guide 3 hari

        $paket_nama = "Paket Kustom";
        if ($destinasi_id > 0) {
            $dest_sql = "SELECT nama_destinasi FROM destinasi WHERE id_destinasi = ?";
            $dest_stmt = $conn->prepare($dest_sql);
            $dest_stmt->bind_param("i", $destinasi_id);
            $dest_stmt->execute();
            $dest_result = $dest_stmt->get_result();
            $dest_data = $dest_result->fetch_assoc();
            $paket_nama .= " - " . $dest_data['nama_destinasi'];
        }
    } else {
        $total_harga = $paket['harga_total'] * $jumlah_orang;
    }

    // Simpan pemesanan
    $insert_sql = "INSERT INTO pemesanan (id_user, id_paket, tanggal_mulai, total_harga, is_custom, status_pemesanan, jumlah_orang) 
                   VALUES (?, ?, ?, ?, ?, 'Pending', ?)";
    $insert_stmt = $conn->prepare($insert_sql);

    $id_paket_insert = $is_custom ? NULL : $paket_id;
    $insert_stmt->bind_param("iisdii", $user_id, $id_paket_insert, $tanggal_mulai, $total_harga, $is_custom, $jumlah_orang);

    if ($insert_stmt->execute()) {
        $pemesanan_id = $insert_stmt->insert_id;

        // Simpan detail untuk paket kustom
        if ($is_custom) {
            // Simpan destinasi_pemesanan
            if ($destinasi_id > 0) {
                $dest_pemesanan_sql = "INSERT INTO destinasi_pemesanan (id_pemesanan, id_destinasi) VALUES (?, ?)";
                $dest_pemesanan_stmt = $conn->prepare($dest_pemesanan_sql);
                $dest_pemesanan_stmt->bind_param("ii", $pemesanan_id, $destinasi_id);
                $dest_pemesanan_stmt->execute();
            }

            // Simpan detail_pemesanan
            if ($provinsi_id > 0 || $hotel_id > 0) {
                $detail_sql = "INSERT INTO detail_pemesanan (id_pemesanan, id_provinsi, id_hotel, jumlah_malam) VALUES (?, ?, ?, 3)";
                $detail_stmt = $conn->prepare($detail_sql);
                $detail_stmt->bind_param("iii", $pemesanan_id, $provinsi_id, $hotel_id);
                $detail_stmt->execute();
            }

            // Simpan catatan khusus
            if ($catatan_khusus) {
                $catatan_sql = "UPDATE pemesanan SET catatan_khusus = ? WHERE id_pemesanan = ?";
                $catatan_stmt = $conn->prepare($catatan_sql);
                $catatan_stmt->bind_param("si", $catatan_khusus, $pemesanan_id);
                $catatan_stmt->execute();
            }
        }

        $_SESSION['order_success'] = true;
        $_SESSION['order_id'] = $pemesanan_id;
        header('Location: orders.php?success=1&id=' . $pemesanan_id);
        exit();
    } else {
        $error = "Terjadi kesalahan saat memproses pemesanan.";
    }
}

// Jika ada parameter success
if (isset($_GET['success'])) {
    $order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $success = true;
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-header" style="background-image: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=1920&auto=format&fit=crop'); min-height: 40vh;">
    <div style="text-align: center; color: white; padding-top: 100px;">
        <h1 data-aos="fade-down">Form Pemesanan</h1>
        <p data-aos="fade-up">Lengkapi data untuk menyelesaikan pemesanan Anda</p>
    </div>
</div>

<div class="order-container" style="padding: 50px 80px; background: #f9f9f9; min-height: 60vh;">
    <?php if (isset($success) && $success): ?>
        <!-- Success Message -->
        <div class="order-success" style="background: white; padding: 40px; border-radius: 10px; text-align: center; max-width: 600px; margin: 0 auto; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
            <div style="background: #00FF44; color: white; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 40px;">
                <i class="fas fa-check"></i>
            </div>
            <h2 style="color: #00AAFF; margin-bottom: 15px;">Pemesanan Berhasil!</h2>
            <p style="color: #666; margin-bottom: 25px;">Terima kasih telah memesan di Jawa Travel. Berikut detail pemesanan Anda:</p>

            <div style="background: #f5f5f5; padding: 20px; border-radius: 8px; margin-bottom: 25px; text-align: left;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong>ID Pemesanan:</strong>
                    <span>#<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong>Paket:</strong>
                    <span><?php echo htmlspecialchars($paket_nama); ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                    <strong>Status:</strong>
                    <span style="color: orange; font-weight: bold;">Pending</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <strong>Tanggal Pemesanan:</strong>
                    <span><?php echo date('d M Y H:i'); ?></span>
                </div>
            </div>

            <p style="color: #888; margin-bottom: 30px; font-size: 14px;">
                Admin kami akan menghubungi Anda dalam 1x24 jam untuk konfirmasi pembayaran.
            </p>

            <div style="display: flex; gap: 15px; justify-content: center;">
                <a href="index.php" class="btn" style="background-color: #00AAFF; color: white; padding: 10px 25px;">Kembali ke Beranda</a>
                <a href="admin_pesanan.php" class="btn" style="background-color: #80D5FF; color: white; padding: 10px 25px;">Lihat Pesanan</a>
            </div>
        </div>
    <?php else: ?>
        <!-- Order Form -->
        <div class="order-form-container" style="max-width: 800px; margin: 0 auto;">
            <?php if (isset($error)): ?>
                <div style="background: #ffe6e6; color: #d00; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="order-summary" style="background: white; padding: 25px; border-radius: 10px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #00AAFF; margin-bottom: 20px;">Ringkasan Pemesanan</h3>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h4 style="color: #666; margin-bottom: 10px;">Data Paket</h4>
                        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                            <p style="margin-bottom: 8px;"><strong>Nama Paket:</strong> <?php echo htmlspecialchars($paket_nama); ?></p>
                            <?php if ($paket): ?>
                                <p style="margin-bottom: 8px;"><strong>Durasi:</strong> <?php echo $paket['jumlah_hari']; ?> Hari</p>
                                <p style="margin-bottom: 8px;"><strong>Harga per Pax:</strong> Rp <?php echo number_format($paket['harga_total'], 0, ',', '.'); ?></p>
                            <?php endif; ?>
                            <?php if ($is_custom): ?>
                                <p style="color: #00AAFF;"><i class="fas fa-star"></i> Paket Kustom Khusus untuk Anda</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <h4 style="color: #666; margin-bottom: 10px;">Data Pemesan</h4>
                        <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                            <p style="margin-bottom: 8px;"><strong>Nama:</strong> <?php echo htmlspecialchars($user['nama']); ?></p>
                            <p style="margin-bottom: 8px;"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                            <p style="margin-bottom: 8px;"><strong>Telepon:</strong> <?php echo htmlspecialchars($user['nomor_telepon'] ?? '-'); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <form method="POST" action="" class="order-form" style="background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h3 style="color: #00AAFF; margin-bottom: 25px;">Detail Pemesanan</h3>

                <div class="form-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: #00AAFF; font-weight: 500;">Jumlah Orang</label>
                        <select name="jumlah_orang" required style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                            <option value="1">1 Orang</option>
                            <option value="2" selected>2 Orang</option>
                            <option value="3">3 Orang</option>
                            <option value="4">4 Orang</option>
                            <option value="5">5 Orang</option>
                            <option value="6">6 Orang</option>
                            <option value="7">7 Orang</option>
                            <option value="8">8 Orang</option>
                            <option value="9">9 Orang</option>
                            <option value="10">10 Orang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label style="display: block; margin-bottom: 8px; color: #00AAFF; font-weight: 500;">Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" required min="<?php echo date('Y-m-d'); ?>" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; color: #00AAFF; font-weight: 500;">Metode Pembayaran</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px;">
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #ddd; border-radius: 5px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="metode_pembayaran" value="transfer" required style="margin-right: 10px;">
                            <div>
                                <div style="font-weight: 600; margin-bottom: 5px;">Transfer Bank</div>
                                <div style="font-size: 12px; color: #888;">BCA, Mandiri, BRI</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #ddd; border-radius: 5px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="metode_pembayaran" value="kartu_kredit" required style="margin-right: 10px;">
                            <div>
                                <div style="font-weight: 600; margin-bottom: 5px;">Kartu Kredit</div>
                                <div style="font-size: 12px; color: #888;">Visa, Mastercard</div>
                            </div>
                        </label>
                        <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #ddd; border-radius: 5px; cursor: pointer; transition: all 0.3s;">
                            <input type="radio" name="metode_pembayaran" value="e-wallet" required style="margin-right: 10px;">
                            <div>
                                <div style="font-weight: 600; margin-bottom: 5px;">E-Wallet</div>
                                <div style="font-size: 12px; color: #888;">GoPay, OVO, Dana</div>
                            </div>
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 8px; color: #00AAFF; font-weight: 500;">Catatan Khusus (Opsional)</label>
                    <textarea name="catatan_khusus" rows="3" placeholder="Contoh: Kamar twin bed, makanan halal, dll." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; resize: vertical;"></textarea>
                </div>

                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="display: flex; align-items: center; cursor: pointer;">
                        <input type="checkbox" required style="margin-right: 10px;">
                        <span style="color: #666;">Saya setuju dengan <a href="#" style="color: #00AAFF;">syarat dan ketentuan</a> yang berlaku</span>
                    </label>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <a href="javascript:history.back()" class="btn" style="background-color: #ccc; color: #333; padding: 12px 25px;">Kembali</a>
                    <button type="submit" class="btn" style="background-color: #00AAFF; color: white; padding: 12px 30px; font-weight: 600; font-size: 16px;">
                        <i class="fas fa-check-circle" style="margin-right: 10px;"></i> Konfirmasi Pemesanan
                    </button>
                </div>
            </form>

            <div class="payment-info" style="background: white; padding: 20px; border-radius: 10px; margin-top: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                <h4 style="color: #00AAFF; margin-bottom: 15px;">Informasi Pembayaran</h4>
                <div style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                    <p style="margin-bottom: 8px; color: #666;"><strong>Setelah mengisi form:</strong></p>
                    <ol style="color: #666; margin-left: 20px;">
                        <li>Admin akan menghubungi Anda untuk konfirmasi ketersediaan</li>
                        <li>Anda akan menerima email dengan detail pembayaran</li>
                        <li>Transfer ke rekening yang tertera dalam email</li>
                        <li>Kirim bukti transfer melalui WhatsApp/email</li>
                        <li>Status akan berubah menjadi "Lunas" setelah pembayaran dikonfirmasi</li>
                    </ol>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'includes/footer.php'; ?>