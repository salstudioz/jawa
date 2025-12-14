<?php
require_once 'includes/db_config.php';
$page_title = 'Kontak Kami - Jawa Travel';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $pesan = $_POST['pesan'];

    // Simpan pesan ke database
    $sql = "INSERT INTO kontak_messages (nama, email, pesan, tanggal) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $nama, $email, $pesan);

    if ($stmt->execute()) {
        $success = "Pesan Anda berhasil dikirim! Kami akan membalas dalam 1x24 jam.";
    } else {
        $error = "Terjadi kesalahan saat mengirim pesan.";
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="kontak-header">
    <div class="kontak-glass-container" data-aos="fade-up">
        <div class="auth-form-title">Kontak Kami</div>
        <div style="text-align: center; color: #00AAFF; font-size: 18px; margin-bottom: 20px;">Beri saran atau Masukan untuk kami</div>

        <?php if (isset($success)): ?>
            <div style="color: green; text-align: center; margin-bottom: 15px; padding: 10px; background: #e6ffe6; border-radius: 5px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div style="color: red; text-align: center; margin-bottom: 15px; padding: 10px; background: #ffe6e6; border-radius: 5px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label class="input-label">Nama</label>
                <input type="text" name="nama" class="input-field" placeholder="Masukan Nama" required>
            </div>
            <div class="input-group">
                <label class="input-label">Email</label>
                <input type="email" name="email" class="input-field" placeholder="Masukan Email" required>
            </div>
            <div class="input-group">
                <label class="input-label">Pesan</label>
                <textarea name="pesan" class="input-field" rows="5" style="border-radius: 20px;" placeholder="Masukan Pesan" required></textarea>
            </div>
            <button type="submit" class="submit-btn">Kirim</button>
        </form>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>