<?php
require_once '../includes/db_config.php';

header('Content-Type: application/json');

// Cek admin
if (!isset($_SESSION['user_id']) || $_SESSION['is_admin'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit();
}

$order_id = isset($_POST['id']) ? intval($_POST['id']) : (isset($_GET['id']) ? intval($_GET['id']) : 0);
$status = isset($_POST['status']) ? $_POST['status'] : (isset($_GET['status']) ? $_GET['status'] : '');

// Validasi status
$allowed_statuses = ['Pending', 'Lunas', 'Dibatalkan'];
if (!in_array($status, $allowed_statuses)) {
    echo json_encode(['success' => false, 'message' => 'Invalid status']);
    exit();
}

if ($order_id > 0) {
    $sql = "UPDATE pemesanan SET status_pemesanan = ? WHERE id_pemesanan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $order_id);

    if ($stmt->execute()) {
        // Log perubahan status
        $log_sql = "INSERT INTO admin_logs (admin_id, action, details, timestamp) 
                    VALUES (?, 'UPDATE_STATUS', ?, NOW())";
        $log_stmt = $conn->prepare($log_sql);
        $details = "Updated order #{$order_id} to status: {$status}";
        $log_stmt->bind_param("is", $_SESSION['user_id'], $details);
        $log_stmt->execute();

        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully',
            'order_id' => $order_id,
            'new_status' => $status
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Update failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
}
