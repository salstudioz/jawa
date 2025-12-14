<?php
require_once 'includes/admin_header.php';
$page_title = 'Kelola Destinasi - Jawa Travel';

// Tambah destinasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama_destinasi = $_POST['nama_destinasi'];
    $deskripsi = $_POST['deskripsi'];
    $id_provinsi = $_POST['id_provinsi'];
    $gambar_url = $_POST['gambar_url'] ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600';

    $sql = "INSERT INTO destinasi (nama_destinasi, deskripsi, id_provinsi, gambar_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssis", $nama_destinasi, $deskripsi, $id_provinsi, $gambar_url);

    if ($stmt->execute()) {
        $success = "Destinasi berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan destinasi!";
    }
}

// Edit destinasi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id_destinasi = $_POST['id_destinasi'];
    $nama_destinasi = $_POST['nama_destinasi'];
    $deskripsi = $_POST['deskripsi'];
    $id_provinsi = $_POST['id_provinsi'];
    $gambar_url = $_POST['gambar_url'];

    $sql = "UPDATE destinasi SET 
            nama_destinasi = ?, 
            deskripsi = ?, 
            id_provinsi = ?, 
            gambar_url = ?
            WHERE id_destinasi = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $nama_destinasi, $deskripsi, $id_provinsi, $gambar_url, $id_destinasi);

    if ($stmt->execute()) {
        $success = "Destinasi berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui destinasi!";
    }
}

// Hapus destinasi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Cek apakah destinasi digunakan di paket
    $check_sql = "SELECT COUNT(*) as total FROM detail_paket_destinasi WHERE id_destinasi = $id";
    $check_result = $conn->query($check_sql);
    $check_data = $check_result->fetch_assoc();

    if ($check_data['total'] > 0) {
        $error = "Destinasi tidak dapat dihapus karena masih digunakan dalam paket wisata!";
    } else {
        $conn->query("DELETE FROM destinasi WHERE id_destinasi = $id");
        $success = "Destinasi berhasil dihapus!";
    }
}

// Ambil data destinasi
$destinasi_result = $conn->query("SELECT d.*, p.nama_provinsi FROM destinasi d 
                                 LEFT JOIN provinsi p ON d.id_provinsi = p.id_provinsi 
                                 ORDER BY d.nama_destinasi");

// Ambil data provinsi untuk form
$provinsi_result = $conn->query("SELECT * FROM provinsi ORDER BY nama_provinsi");

// Ambil data untuk edit
$edit_destinasi = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM destinasi WHERE id_destinasi = $edit_id");
    $edit_destinasi = $edit_result->fetch_assoc();
}
?>

<h1 class="page-title">Kelola Destinasi Wisata</h1>

<div class="action-bar">
    <button class="btn-add" onclick="toggleForm('tambah')">Tambah Destinasi</button>
</div>

<?php if (isset($success)): ?>
    <div style="background: #d4edda; color: #155724; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
        <?php echo $success; ?>
    </div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div style="background: #f8d7da; color: #721c24; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px;">
        <?php echo $error; ?>
    </div>
<?php endif; ?>

<!-- Form Tambah/Edit Destinasi -->
<div id="formContainer" style="display: <?php echo ($edit_destinasi || isset($_GET['showForm'])) ? 'block' : 'none'; ?>; margin-bottom: 30px; background: #f5f5f5; padding: 25px; border-radius: 10px;">
    <h3 style="margin-bottom: 20px; color: #00AAFF;">
        <?php echo $edit_destinasi ? 'Edit Destinasi' : 'Tambah Destinasi Baru'; ?>
    </h3>

    <form method="POST" action="">
        <?php if ($edit_destinasi): ?>
            <input type="hidden" name="id_destinasi" value="<?php echo $edit_destinasi['id_destinasi']; ?>">
            <input type="hidden" name="edit" value="1">
        <?php else: ?>
            <input type="hidden" name="tambah" value="1">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Destinasi *</label>
                <input type="text" name="nama_destinasi" value="<?php echo $edit_destinasi['nama_destinasi'] ?? ''; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Provinsi *</label>
                <select name="id_provinsi" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <option value="">Pilih Provinsi</option>
                    <?php while ($prov = $provinsi_result->fetch_assoc()): ?>
                        <option value="<?php echo $prov['id_provinsi']; ?>"
                            <?php echo ($edit_destinasi && $edit_destinasi['id_provinsi'] == $prov['id_provinsi']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prov['nama_provinsi']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">URL Gambar</label>
            <input type="text" name="gambar_url" value="<?php echo $edit_destinasi['gambar_url'] ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'; ?>"
                style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Masukkan URL gambar">
            <small style="color: #666;">Biarkan kosong untuk menggunakan gambar default</small>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Deskripsi</label>
            <textarea name="deskripsi" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo $edit_destinasi['deskripsi'] ?? ''; ?></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-add">
                <?php echo $edit_destinasi ? 'Update Destinasi' : 'Simpan Destinasi'; ?>
            </button>
            <button type="button" onclick="toggleForm('tambah')" style="background: #ccc; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Batal
            </button>
            <?php if ($edit_destinasi): ?>
                <a href="admin_destinasi.php" style="background: #666; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
                    Kembali ke List
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
                <th>Nama Destinasi</th>
                <th>Provinsi</th>
                <th>Deskripsi</th>
                <th style="width: 150px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $destinasi_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_destinasi']; ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="<?php echo htmlspecialchars($row['gambar_url']); ?>" alt="<?php echo htmlspecialchars($row['nama_destinasi']); ?>"
                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                            <div>
                                <strong><?php echo htmlspecialchars($row['nama_destinasi']); ?></strong><br>
                                <small style="color: #666;">ID: <?php echo $row['id_destinasi']; ?></small>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($row['nama_provinsi']); ?></td>
                    <td>
                        <div style="max-height: 60px; overflow: hidden;">
                            <?php echo substr($row['deskripsi'], 0, 100); ?>...
                        </div>
                    </td>
                    <td>
                        <a href="?edit=<?php echo $row['id_destinasi']; ?>" class="btn-action btn-edit">Edit</a>
                        <button class="btn-action btn-delete" onclick="hapusDestinasi(<?php echo $row['id_destinasi']; ?>)">Hapus</button>
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
            // Clear form jika cancel
            if (type === 'tambah') {
                window.location.href = 'admin_destinasi.php';
            }
        }
    }

    function hapusDestinasi(id) {
        if (confirm('Apakah Anda yakin ingin menghapus destinasi ini?\n\nPerhatian: Pastikan destinasi tidak digunakan dalam paket wisata.')) {
            window.location.href = '?hapus=' + id;
        }
    }
</script>

</main>
</body>

</html>