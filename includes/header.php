<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'Jawa Travel'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="logo"><a href="index.php" style="color: #000; text-decoration: none;">Jawa</a></div>

        <!-- Mobile Menu Toggle -->
        <div class="menu-toggle" id="mobile-menu">
            <i class="fas fa-bars"></i>
        </div>

        <ul class="nav-links" id="nav-links">
            <li><a href="index.php#paket" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Paket Pariwisata</a></li>
            <li><a href="index.php#kustom" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">Cari Paket</a></li>
            <li class="dropdown">
                <span>Info Lainnya <i class="fas fa-chevron-down" style="font-size: 10px;"></i></span>
                <div class="dropdown-content">
                    <a href="tentang.php" class="<?php echo ($current_page == 'tentang.php') ? 'active' : ''; ?>">Tentang Kami</a>
                    <a href="faq.php" class="<?php echo ($current_page == 'faq.php') ? 'active' : ''; ?>">FAQ</a>
                    <a href="kontak.php" class="<?php echo ($current_page == 'kontak.php') ? 'active' : ''; ?>">Kontak</a>
                </div>
            </li>
        </ul>
        <div class="nav-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="my_orders.php" class="btn btn-daftar">Pesanan Saya</a>
                <a href="logout.php" class="btn btn-daftar">Logout</a>
                <?php if ($_SESSION['is_admin'] == 1): ?>
                    <a href="admin_dashboard.php" class="btn btn-masuk">Admin</a>
                <?php endif; ?>
            <?php else: ?>
                <a href="daftar.php" class="btn btn-daftar">Daftar</a>
                <a href="masuk.php" class="btn btn-masuk">Masuk</a>
            <?php endif; ?>
        </div>
    </nav>