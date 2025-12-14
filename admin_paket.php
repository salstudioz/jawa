<?php
require_once 'includes/admin_header.php';
$page_title = 'Kelola Paket - Jawa Travel';

// Tambah paket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah'])) {
    $nama_paket = $_POST['nama_paket'];
    $deskripsi = $_POST['deskripsi'];
    $harga_total = $_POST['harga_total'];
    $jumlah_hari = $_POST['jumlah_hari'];
    $id_provinsi = $_POST['id_provinsi'];
    $rating = $_POST['rating'] ?? 4.0;
    $gambar_url = $_POST['gambar_url'] ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600';

    // Ambil destinasi yang dipilih
    $destinasi_ids = isset($_POST['destinasi_ids']) ? $_POST['destinasi_ids'] : [];

    $sql = "INSERT INTO paket_wisata (nama_paket, deskripsi, harga_total, jumlah_hari, id_provinsi, rating, gambar_url) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiids", $nama_paket, $deskripsi, $harga_total, $jumlah_hari, $id_provinsi, $rating, $gambar_url);

    if ($stmt->execute()) {
        $paket_id = $stmt->insert_id;

        // Simpan destinasi untuk paket ini
        foreach ($destinasi_ids as $destinasi_id) {
            $dest_sql = "INSERT INTO detail_paket_destinasi (id_paket, id_destinasi) VALUES (?, ?)";
            $dest_stmt = $conn->prepare($dest_sql);
            $dest_stmt->bind_param("ii", $paket_id, $destinasi_id);
            $dest_stmt->execute();
        }

        $success = "Paket berhasil ditambahkan!";
    } else {
        $error = "Gagal menambahkan paket!";
    }
}

// Edit paket
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id_paket = $_POST['id_paket'];
    $nama_paket = $_POST['nama_paket'];
    $deskripsi = $_POST['deskripsi'];
    $harga_total = $_POST['harga_total'];
    $jumlah_hari = $_POST['jumlah_hari'];
    $id_provinsi = $_POST['id_provinsi'];
    $rating = $_POST['rating'] ?? 4.0;
    $gambar_url = $_POST['gambar_url'];

    $sql = "UPDATE paket_wisata SET 
            nama_paket = ?, 
            deskripsi = ?, 
            harga_total = ?, 
            jumlah_hari = ?, 
            id_provinsi = ?, 
            rating = ?, 
            gambar_url = ?
            WHERE id_paket = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdiidsi", $nama_paket, $deskripsi, $harga_total, $jumlah_hari, $id_provinsi, $rating, $gambar_url, $id_paket);

    if ($stmt->execute()) {
        // Hapus destinasi lama
        $conn->query("DELETE FROM detail_paket_destinasi WHERE id_paket = $id_paket");

        // Tambah destinasi baru
        if (isset($_POST['destinasi_ids'])) {
            foreach ($_POST['destinasi_ids'] as $destinasi_id) {
                $dest_sql = "INSERT INTO detail_paket_destinasi (id_paket, id_destinasi) VALUES (?, ?)";
                $dest_stmt = $conn->prepare($dest_sql);
                $dest_stmt->bind_param("ii", $id_paket, $destinasi_id);
                $dest_stmt->execute();
            }
        }

        $success = "Paket berhasil diperbarui!";
    } else {
        $error = "Gagal memperbarui paket!";
    }
}

// Hapus paket
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $conn->query("DELETE FROM detail_paket_destinasi WHERE id_paket = $id");
    $conn->query("DELETE FROM paket_wisata WHERE id_paket = $id");
    $success = "Paket berhasil dihapus!";
}

// Ambil data paket
$paket_result = $conn->query("SELECT p.*, pr.nama_provinsi FROM paket_wisata p 
                              LEFT JOIN provinsi pr ON p.id_provinsi = pr.id_provinsi 
                              ORDER BY p.id_paket DESC");

// Ambil data untuk form
$provinsi_result = $conn->query("SELECT * FROM provinsi ORDER BY nama_provinsi");
$destinasi_result = $conn->query("SELECT d.*, p.nama_provinsi FROM destinasi d 
                                 LEFT JOIN provinsi p ON d.id_provinsi = p.id_provinsi 
                                 ORDER BY d.nama_destinasi");

// Ambil data untuk edit
$edit_paket = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $edit_result = $conn->query("SELECT * FROM paket_wisata WHERE id_paket = $edit_id");
    $edit_paket = $edit_result->fetch_assoc();

    // Ambil destinasi untuk paket ini
    $edit_destinasi = $conn->query("SELECT id_destinasi FROM detail_paket_destinasi WHERE id_paket = $edit_id");
    $selected_destinasi = [];
    while ($row = $edit_destinasi->fetch_assoc()) {
        $selected_destinasi[] = $row['id_destinasi'];
    }
}
?>

<h1 class="page-title">Kelola Paket Wisata</h1>

<div class="action-bar">
    <button class="btn-add" onclick="toggleForm('tambah')">Tambah Paket</button>
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

<!-- Form Tambah/Edit Paket -->
<div id="formContainer" style="display: <?php echo ($edit_paket || isset($_GET['showForm'])) ? 'block' : 'none'; ?>; margin-bottom: 30px; background: #f5f5f5; padding: 25px; border-radius: 10px;">
    <h3 style="margin-bottom: 20px; color: #00AAFF;">
        <?php echo $edit_paket ? 'Edit Paket' : 'Tambah Paket Baru'; ?>
    </h3>

    <form method="POST" action="">
        <?php if ($edit_paket): ?>
            <input type="hidden" name="id_paket" value="<?php echo $edit_paket['id_paket']; ?>">
            <input type="hidden" name="edit" value="1">
        <?php else: ?>
            <input type="hidden" name="tambah" value="1">
        <?php endif; ?>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Nama Paket *</label>
                <input type="text" name="nama_paket" value="<?php echo $edit_paket['nama_paket'] ?? ''; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Provinsi *</label>
                <select name="id_provinsi" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
                    <option value="">Pilih Provinsi</option>
                    <?php while ($prov = $provinsi_result->fetch_assoc()): ?>
                        <option value="<?php echo $prov['id_provinsi']; ?>"
                            <?php echo ($edit_paket && $edit_paket['id_provinsi'] == $prov['id_provinsi']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($prov['nama_provinsi']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Harga Total *</label>
                <input type="number" name="harga_total" value="<?php echo $edit_paket['harga_total'] ?? ''; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Jumlah Hari *</label>
                <input type="number" name="jumlah_hari" value="<?php echo $edit_paket['jumlah_hari'] ?? '3'; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">Rating</label>
                <select name="rating" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                    <option value="4.0" <?php echo ($edit_paket && $edit_paket['rating'] == 4.0) ? 'selected' : ''; ?>>4.0 Bintang</option>
                    <option value="4.5" <?php echo ($edit_paket && $edit_paket['rating'] == 4.5) ? 'selected' : ''; ?>>4.5 Bintang</option>
                    <option value="5.0" <?php echo ($edit_paket && $edit_paket['rating'] == 5.0) ? 'selected' : ''; ?>>5.0 Bintang</option>
                </select>
            </div>
            <div>
                <label style="display: block; margin-bottom: 5px; font-weight: 500;">URL Gambar</label>
                <input type="text" name="gambar_url" value="<?php echo $edit_paket['gambar_url'] ?? 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?q=80&w=600'; ?>"
                    style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;" placeholder="Masukkan URL gambar">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Destinasi (Bisa pilih banyak)</label>
            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; border-radius: 5px; padding: 10px;">
                <?php
                // Reset pointer destinasi
                $destinasi_result->data_seek(0);
                while ($dest = $destinasi_result->fetch_assoc()):
                ?>
                    <label style="display: block; margin-bottom: 5px; padding: 5px; background: #f9f9f9; border-radius: 3px;">
                        <input type="checkbox" name="destinasi_ids[]" value="<?php echo $dest['id_destinasi']; ?>"
                            <?php echo (isset($selected_destinasi) && in_array($dest['id_destinasi'], $selected_destinasi)) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($dest['nama_destinasi']); ?>
                        <small>(<?php echo htmlspecialchars($dest['nama_provinsi']); ?>)</small>
                    </label>
                <?php endwhile; ?>
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 5px; font-weight: 500;">Deskripsi</label>
            <textarea name="deskripsi" rows="4" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;"><?php echo $edit_paket['deskripsi'] ?? ''; ?></textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" class="btn-add">
                <?php echo $edit_paket ? 'Update Paket' : 'Simpan Paket'; ?>
            </button>
            <button type="button" onclick="toggleForm('tambah')" style="background: #ccc; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                Batal
            </button>
            <?php if ($edit_paket): ?>
                <a href="admin_paket.php" style="background: #666; color: white; padding: 10px 20px; border-radius: 5px; text-decoration: none;">
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
                <th>Nama Paket</th>
                <th>Provinsi</th>
                <th>Harga Total</th>
                <th>Hari</th>
                <th>Rating</th>
                <th style="width: 180px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $paket_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_paket']; ?></td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <img src="<?php echo htmlspecialchars($row['gambar_url']); ?>" alt="<?php echo htmlspecialchars($row['nama_paket']); ?>"
                                style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;">
                            <div>
                                <strong><?php echo htmlspecialchars($row['nama_paket']); ?></strong><br>
                                <small style="color: #666;"><?php echo substr($row['deskripsi'], 0, 50); ?>...</small>
                            </div>
                        </div>
                    </td>
                    <td><?php echo htmlspecialchars($row['nama_provinsi']); ?></td>
                    <td>Rp <?php echo number_format($row['harga_total'], 0, ',', '.'); ?></td>
                    <td><?php echo $row['jumlah_hari']; ?> Hari</td>
                    <td>
                        <div style="color: #FFD700;">
                            <?php
                            $rating = $row['rating'] ?? 4.0;
                            $full_stars = floor($rating);
                            $has_half = $rating - $full_stars >= 0.5;

                            for ($i = 1; $i <= 5; $i++):
                                if ($i <= $full_stars):
                                    echo '<i class="fas fa-star"></i>';
                                elseif ($i == $full_stars + 1 && $has_half):
                                    echo '<i class="fas fa-star-half-alt"></i>';
                                else:
                                    echo '<i class="far fa-star"></i>';
                                endif;
                            endfor;
                            ?>
                            <span style="color: #666;">(<?php echo number_format($rating, 1); ?>)</span>
                        </div>
                    </td>
                    <td>
                        <a href="?edit=<?php echo $row['id_paket']; ?>" class="btn-action btn-edit">Edit</a>
                        <button class="btn-action btn-delete" onclick="hapusPaket(<?php echo $row['id_paket']; ?>)">Hapus</button>
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
                window.location.href = 'admin_paket.php';
            }
        }
    }

    function hapusPaket(id) {
        if (confirm('Apakah Anda yakin ingin menghapus paket ini?\n\nCatatan: Destinasi yang terkait akan tetap ada.')) {
            window.location.href = '?hapus=' + id;
        }
    }
</script>

</main>
</body>

</html>