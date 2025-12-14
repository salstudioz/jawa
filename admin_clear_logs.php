<?php
require_once 'includes/admin_header.php';
$page_title = 'Log Aktivitas Admin';

// Buat tabel logs jika belum ada
$conn->query("CREATE TABLE IF NOT EXISTS admin_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    admin_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    details TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (admin_id) REFERENCES user(id_user)
)");

// Ambil logs
$logs_sql = "SELECT al.*, u.nama as admin_name 
             FROM admin_logs al
             LEFT JOIN user u ON al.admin_id = u.id_user
             ORDER BY al.timestamp DESC
             LIMIT 100";
$logs_result = $conn->query($logs_sql);
?>

<h1 class="page-title">Log Aktivitas Admin</h1>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Admin</th>
                <th>Aksi</th>
                <th>Detail</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($logs_result->num_rows > 0): ?>
                <?php while ($log = $logs_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $log['id']; ?></td>
                        <td><?php echo htmlspecialchars($log['admin_name']); ?></td>
                        <td><span class="status-badge status-pending"><?php echo htmlspecialchars($log['action']); ?></span></td>
                        <td><?php echo htmlspecialchars($log['details']); ?></td>
                        <td><?php echo date('d M Y H:i', strtotime($log['timestamp'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px;">
                        <i class="fas fa-history" style="font-size: 40px; color: #ddd; margin-bottom: 10px;"></i><br>
                        Belum ada log aktivitas
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div style="margin-top: 20px; text-align: center;">
    <button onclick="clearLogs()" class="btn-action btn-delete">
        <i class="fas fa-trash"></i> Hapus Semua Log
    </button>
</div>

<script>
    function clearLogs() {
        if (confirm('Apakah Anda yakin ingin menghapus semua log?\n\nTindakan ini tidak dapat dibatalkan.')) {
            fetch('admin_clear_logs.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Log berhasil dihapus!');
                        location.reload();
                    } else {
                        alert('Gagal menghapus log: ' + data.message);
                    }
                });
        }
    }
</script>

</main>
</body>

</html>