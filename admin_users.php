<?php
require_once 'includes/admin_header.php';
$page_title = 'Kelola User - Jawa Travel';

// Tambah user baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $nomor_telepon = $_POST['nomor_telepon'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Cek email sudah ada
    $check = $conn->query("SELECT id_user FROM user WHERE email = '$email'");
    if ($check->num_rows > 0) {
        $error = "Email sudah terdaftar!";
    } else {
        $sql = "INSERT INTO user (nama, email, password, nomor_telepon, alamat, is_admin) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $email, $password, $nomor_telepon, $alamat, $is_admin);

        if ($stmt->execute()) {
            // Log aktivitas
            $log_sql = "INSERT INTO admin_logs (admin_id, action, details, timestamp) 
                        VALUES (?, 'TAMBAH_USER', ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $details = "Menambahkan user baru: " . $email;
            $log_stmt->bind_param("is", $_SESSION['user_id'], $details);
            $log_stmt->execute();

            $success = "User berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan user!";
        }
    }
}

// Edit user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $nomor_telepon = $_POST['nomor_telepon'] ?? '';
    $alamat = $_POST['alamat'] ?? '';
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    // Cek email unik (kecuali untuk user ini)
    $check = $conn->query("SELECT id_user FROM user WHERE email = '$email' AND id_user != $id_user");
    if ($check->num_rows > 0) {
        $error = "Email sudah digunakan user lain!";
    } else {
        // Update tanpa password
        $sql = "UPDATE user SET 
                nama = ?, 
                email = ?, 
                nomor_telepon = ?, 
                alamat = ?, 
                is_admin = ?
                WHERE id_user = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama, $email, $nomor_telepon, $alamat, $is_admin, $id_user);

        if ($stmt->execute()) {
            // Log aktivitas
            $log_sql = "INSERT INTO admin_logs (admin_id, action, details, timestamp) 
                        VALUES (?, 'EDIT_USER', ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $details = "Mengedit user ID: " . $id_user;
            $log_stmt->bind_param("is", $_SESSION['user_id'], $details);
            $log_stmt->execute();

            $success = "User berhasil diperbarui!";
        } else {
            $error = "Gagal memperbarui user!";
        }
    }
}

// Reset password user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reset_password'])) {
    $id_user = $_POST['id_user'];
    $new_password = password_hash('12345678', PASSWORD_DEFAULT); // Password default

    $sql = "UPDATE user SET password = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_password, $id_user);

    if ($stmt->execute()) {
        // Log aktivitas
        $log_sql = "INSERT INTO admin_logs (admin_id, action, details, timestamp) 
                    VALUES (?, 'RESET_PASSWORD', ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $details = "Reset password user ID: " . $id_user;
        $log_stmt->bind_param("is", $_SESSION['user_id'], $details);
        $log_stmt->execute();

        $success = "Password berhasil direset ke: 12345678";
    } else {
        $error = "Gagal reset password!";
    }
}

// Hapus user
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Cek apakah user admin
    $check_admin = $conn->query("SELECT is_admin FROM user WHERE id_user = $id");
    $user_data = $check_admin->fetch_assoc();

    if ($user_data['is_admin'] == 1) {
        $error = "Tidak dapat menghapus user admin!";
    } else {
        // Cek apakah user memiliki pesanan
        $check_order = $conn->query("SELECT COUNT(*) as total FROM pemesanan WHERE id_user = $id");
        $order_data = $check_order->fetch_assoc();

        if ($order_data['total'] > 0) {
            $error = "User tidak dapat dihapus karena memiliki riwayat pesanan!";
        } else {
            $conn->query("DELETE FROM user WHERE id_user = $id");

            // Log aktivitas
            $log_sql = "INSERT INTO admin_logs (admin_id, action, details, timestamp) 
                        VALUES (?, 'HAPUS_USER', ?, NOW())";
            $log_stmt = $conn->prepare($log_sql);
            $details = "Menghapus user ID: " . $id;
            $log_stmt->bind_param("is", $_SESSION['user_id'], $details);
            $log_stmt->execute();

            $success = "User berhasil dihapus!";
        }
    }
}

// Ambil data user (exclude admin yang sedang login)
$current_admin_id = isset($admin_id) ? (int)$admin_id : (isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : 0);
$user_result = $conn->query("SELECT * FROM user WHERE id_user != {$current_admin_id} ORDER BY is_admin DESC, nama ASC");
// Hitung statistik user
$total_users = $conn->query("SELECT COUNT(*) as total FROM user")->fetch_assoc()['total'];
$total_admin = $conn->query("SELECT COUNT(*) as total FROM user WHERE is_admin = 1")->fetch_assoc()['total'];
$total_regular = $total_users - $total_admin;

// Ambil data untuk edit
$edit_user = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM user WHERE id_user = $edit_id");
    $edit_user = $edit_result->fetch_assoc();
}
?>

<h1 class="page-title">Kelola User</h1>

<div class="dashboard-cards" style="margin-bottom: 30px;">
    <div class="card">
        <div class="card-title">Total User</div>
        <div class="card-value"><?php echo $total_users; ?></div>
    </div>
    <div class="card">
        <div class="card-title">Administrator</div>
        <div class="card-value"><?php echo $total_admin; ?></div>
    </div>
    <div class="card">
        <div class="card-title">User Regular</div>
        <div class="card-value"><?php echo $total_regular; ?></div>
    </div>
</div>

<div class="action-bar">
    <button class="btn-add" onclick="toggleForm('tambah')">
        <i class="fas fa-user-plus"></i> Tambah User
    </button>
    <div style="display: flex; gap: 10px;">
        <a href="?filter=all" class="btn-add" style="background: <?php echo (!isset($_GET['filter']) || $_GET['filter'] == 'all') ? '#0072AA' : '#00AAFF'; ?>;">
            Semua
        </a>
        <a href="?filter=admin" class="btn-add" style="background: <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'admin') ? '#0072AA' : '#00AAFF'; ?>;">
            Admin
        </a>
        <a href="?filter=user" class="btn-add" style="background: <?php echo (isset($_GET['filter']) && $_GET['filter'] == 'user') ? '#0072AA' : '#00AAFF'; ?>;">
            User
        </a>
    </div>
</div>

<?php if (isset($success)): ?>
    <div class="alert alert-success">
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!-- Form Tambah/Edit User -->
<div id="formContainer" style="display: <?php echo ($edit_user || isset($_GET['showForm'])) ? 'block' : 'none'; ?>; margin-bottom: 30px; background: #f5f5f5; padding: 25px; border-radius: 10px;">
    <h3 style="margin-bottom: 20px; color: #00AAFF;">
        <i class="fas fa-user-edit"></i> <?php echo $edit_user ? 'Edit User' : 'Tambah User Baru'; ?>
    </h3>

    <form method="POST" action="">
        <?php if ($edit_user): ?>
            <input type="hidden" name="id_user" value="<?php echo $edit_user['id_user']; ?>">
            <input type="hidden" name="edit" value="1">
        <?php else: ?>
            <input type="hidden" name="tambah" value="1">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label class="form-label">Nama Lengkap *</label>
                <input type="text" name="nama" value="<?php echo $edit_user['nama'] ?? ''; ?>"
                    class="form-control" required>
            </div>
            <div>
                <label class="form-label">Email *</label>
                <input type="email" name="email" value="<?php echo $edit_user['email'] ?? ''; ?>"
                    class="form-control" required>
            </div>
            <?php if (!$edit_user): ?>
                <div>
                    <label class="form-label">Password *</label>
                    <input type="password" name="password" class="form-control" required>
                    <small style="color: #666;">Minimal 8 karakter</small>
                </div>
            <?php endif; ?>
            <div>
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" value="<?php echo $edit_user['nomor_telepon'] ?? ''; ?>"
                    class="form-control">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="3" class="form-control"><?php echo $edit_user['alamat'] ?? ''; ?></textarea>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" name="is_admin" value="1"
                    <?php echo ($edit_user && $edit_user['is_admin'] == 1) ? 'checked' : ''; ?>>
                <span style="font-weight: 500;">Jadikan sebagai Administrator</span>
            </label>
            <small style="color: #666; display: block; margin-top: 5px;">
                Administrator memiliki akses penuh ke sistem admin
            </small>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-add">
                <i class="fas fa-save"></i> <?php echo $edit_user ? 'Update User' : 'Simpan User'; ?>
            </button>
            <button type="button" onclick="toggleForm('tambah')" style="background: #ccc; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                <i class="fas fa-times"></i> Batal
            </button>
            <?php if ($edit_user): ?>
                <!-- Reset Password Form -->
                <form method="POST" action="" style="margin: 0;">
                    <input type="hidden" name="id_user" value="<?php echo $edit_user['id_user']; ?>">
                    <input type="hidden" name="reset_password" value="1">
                    <button type="submit" class="btn-action" style="background-color: #FFA500;" onclick="return confirm('Reset password user ini menjadi 12345678?')">
                        <i class="fas fa-key"></i> Reset Password
                    </button>
                </form>
                <a href="admin_user.php" class="btn-action" style="background-color: #666;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th style="width: 50px;">ID</th>
                <th>Nama User</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Role</th>
                <th>Tanggal Daftar</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $user_result->fetch_assoc()):
                $role_class = $row['is_admin'] == 1 ? 'status-lunas' : 'status-pending';
                $role_text = $row['is_admin'] == 1 ? 'Admin' : 'User';
            ?>
                <tr>
                    <td><?php echo $row['id_user']; ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div class="admin-avatar" style="width: 40px; height: 40px;">
                                <?php echo substr($row['nama'], 0, 1); ?>
                            </div>
                            <div>
                                <strong><?php echo htmlspecialchars($row['nama']); ?></strong><br>
                                <small style="color: #666;">ID: <?php echo $row['id_user']; ?></small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php echo htmlspecialchars($row['email']); ?><br>
                        <small style="color: #666;"><?php echo substr($row['password'], 0, 15); ?>...</small>
                    </td>
                    <td><?php echo $row['nomor_telepon'] ?: '-'; ?></td>
                    <td>
                        <span class="status-badge <?php echo $role_class; ?>">
                            <?php echo $role_text; ?>
                        </span>
                    </td>
                    <td>
                        <?php echo date('d M Y', strtotime($row['created_at'] ?? 'now')); ?><br>
                        <small style="color: #666;">
                            <?php
                            $date = new DateTime($row['created_at'] ?? 'now');
                            echo $date->format('H:i');
                            ?>
                        </small>
                    </td>
                    <td>
                        <a href="?edit=<?php echo $row['id_user']; ?>" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button class="btn-action btn-delete" onclick="hapusUser(<?php echo $row['id_user']; ?>, '<?php echo htmlspecialchars($row['nama']); ?>', <?php echo $row['is_admin']; ?>)">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script>
    function toggleForm(type) {
        var form = document.getElementById('formContainer');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            window.scrollTo({
                top: form.offsetTop - 50,
                behavior: 'smooth'
            });
        } else {
            form.style.display = 'none';
            if (type === 'tambah') {
                window.location.href = 'admin_user.php';
            }
        }
    }

    function hapusUser(id, name, isAdmin) {
        if (isAdmin == 1) {
            alert('Tidak dapat menghapus user admin!');
            return false;
        }

        if (confirm('Apakah Anda yakin ingin menghapus user "' + name + '"?\n\nCatatan: User dengan riwayat pesanan tidak dapat dihapus.')) {
            window.location.href = '?hapus=' + id;
        }
    }

    // Filter user berdasarkan role
    function filterUsers(role) {
        window.location.href = '?filter=' + role;
    }
</script>

</main>
</body>

</html>