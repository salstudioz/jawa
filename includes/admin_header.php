<?php
require_once 'db_config.php';
$current_page = basename($_SERVER['PHP_SELF']);

// Cek admin login
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    header('Location: ../masuk.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Admin - Jawa Travel'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin_style.css">
    <style>
        /* Tambahan untuk admin */
        .main-content {
            margin-left: 280px;
            width: calc(100% - 280px);
            padding: 40px 60px;
        }

        .page-title {
            font-size: 40px;
            font-weight: 700;
            color: #00AAFF;
            margin-bottom: 40px;
        }

        @media (max-width: 768px) {
            .main-content {
                margin-left: 70px;
                width: calc(100% - 70px);
                padding: 20px;
            }
        }
    </style>
</head>

<body>
    <aside class="sidebar">
        <div class="sidebar-header">Hallo, <?php echo $_SESSION['nama']; ?>!</div>
        <nav class="nav-menu">
            <a href="admin_dashboard.php" class="nav-item <?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
            <a href="admin_paket.php" class="nav-item <?php echo ($current_page == 'admin_paket.php') ? 'active' : ''; ?>">Kelola Paket Wisata</a>
            <a href="admin_destinasi.php" class="nav-item <?php echo ($current_page == 'admin_destinasi.php') ? 'active' : ''; ?>">Kelola Destinasi Wisata</a>
            <a href="admin_pesanan.php" class="nav-item <?php echo ($current_page == 'admin_pesanan.php') ? 'active' : ''; ?>">Daftar Pesanan Masuk</a>
            <a href="admin_users.php" class="nav-item <?php echo ($current_page == 'admin_users.php') ? 'active' : ''; ?>">Daftar Pengguna</a>
            <a href="admin_logs.php" class="nav-item <?php echo ($current_page == 'admin_logs.php') ? 'active' : ''; ?>">Log Aktivitas</a>
            <a href="../logout.php" class="nav-item" style="color: #ff6b6b; margin-top: 50px;">Logout</a>
        </nav>
    </aside>
    <main class="main-content">