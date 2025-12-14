<?php
require_once 'includes/db_config.php';
$page_title = 'Daftar - Jawa Travel';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nomor_telepon = $_POST['nomor_telepon'];

    // Validasi
    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Cek email sudah terdaftar
        $check_sql = "SELECT id_user FROM user WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user
            $sql = "INSERT INTO user (nama, email, password, nomor_telepon, tanggal_daftar, is_admin) 
                    VALUES (?, ?, ?, ?, NOW(), 0)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nama, $email, $hashed_password, $nomor_telepon);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
                $_SESSION['nama'] = $nama;
                $_SESSION['email'] = $email;
                $_SESSION['is_admin'] = 0;

                header('Location: index.php');
                exit();
            } else {
                $error = "Terjadi kesalahan saat mendaftar!";
            }
        }
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-header" style="background-image: url('https://images.unsplash.com/photo-1504829857797-ddff29c27927?q=80&w=1920&auto=format&fit=crop');">
    <div class="glass-container" data-aos="zoom-in">
        <div class="auth-content">
            <div class="auth-title">Hallo Traveler!</div>
            <div class="auth-subtitle">Mulai Perjalananmu<br>Dari Sekarang</div>
            <p style="margin-top: 10px; margin-bottom: 20px;">Klik tombol masuk jika sudah punya akun</p>
            <a href="masuk.php" class="btn btn-masuk" style="width: fit-content;">Masuk</a>
        </div>

        <div class="auth-form-container">
            <div class="auth-form-title">Daftar</div>
            <div style="text-align: center; color: #727272; margin-bottom: 20px;">Perjalananmu dimulai dari sini</div>

            <?php if (isset($error)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px; padding: 10px; background: #ffe6e6; border-radius: 5px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="input-group">
                    <label class="input-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="input-field" placeholder="Masukan Nama Lengkap" required>
                </div>
                <div class="input-group">
                    <label class="input-label">Email</label>
                    <input type="email" name="email" class="input-field" placeholder="Masukan Email" required>
                </div>
                <div class="input-group">
                    <label class="input-label">Nomor Telepon</label>
                    <input type="tel" name="nomor_telepon" class="input-field" placeholder="Masukan Nomor Telepon">
                </div>
                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Masukan Password" required>
                </div>
                <div class="input-group">
                    <label class="input-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="input-field" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" class="submit-btn" style="width: 100%;">Daftar</button>
            </form>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>