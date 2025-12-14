<?php
require_once 'includes/admin_header.php';
$page_title = 'Dashboard Admin - Jawa Travel';

// Hitung statistik
$total_paket = $conn->query("SELECT COUNT(*) as total FROM paket_wisata")->fetch_assoc()['total'];
$total_pemesanan = $conn->query("SELECT COUNT(*) as total FROM pemesanan")->fetch_assoc()['total'];
$total_user = $conn->query("SELECT COUNT(*) as total FROM user WHERE is_admin = 0")->fetch_assoc()['total'];
?>

<h1 class="page-title">Dashboard Administrasi</h1>

<div class="dashboard-cards">
    <div class="card">
        <div class="card-title">Total Paket</div>
        <div class="card-value"><?php echo $total_paket; ?></div>
    </div>
    <div class="card">
        <div class="card-title">Total Pemesanan</div>
        <div class="card-value"><?php echo $total_pemesanan; ?></div>
    </div>
    <div class="card">
        <div class="card-title">User Terdaftar</div>
        <div class="card-value"><?php echo $total_user; ?></div>
    </div>
</div>

</main>
</body>

</html>