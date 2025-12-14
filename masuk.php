<?php
require_once 'includes/db_config.php';
$page_title = 'Masuk - Jawa Travel';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['nama'] = $user['nama'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['is_admin'] = $user['is_admin'];

            if ($user['is_admin'] == 1) {
                header('Location: admin_dashboard.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="page-header" style="background-image: url('https://images.unsplash.com/photo-1504829857797-ddff29c27927?q=80&w=1920&auto=format&fit=crop');">
    <div class="glass-container" data-aos="zoom-in">
        <div class="auth-form-container">
            <div class="auth-form-title">Masuk</div>
            <div style="text-align: center; color: #727272; margin-bottom: 20px;">Perjalananmu dimulai dari sini</div>

            <?php if (isset($error)): ?>
                <div style="color: red; text-align: center; margin-bottom: 15px; padding: 10px; background: #ffe6e6; border-radius: 5px;">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="input-group">
                    <label class="input-label">Email</label>
                    <input type="email" name="email" class="input-field" placeholder="Masukan Email" required>
                </div>
                <div class="input-group">
                    <label class="input-label">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Masukan Password" required>
                </div>
                <button type="submit" class="submit-btn" style="width: 100%;">Masuk</button>
            </form>
        </div>

        <div class="auth-content" style="text-align: right; align-items: flex-end;">
            <div class="auth-title">Hallo Traveler!</div>
            <div class="auth-subtitle">Mulai Perjalananmu<br>Dari Sekarang</div>
            <p style="margin-top: 10px; margin-bottom: 20px;">Klik tombol Daftar jika belum punya akun</p>
            <a href="daftar.php" class="btn btn-masuk" style="width: fit-content;">Daftar</a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>