<?php
require_once 'includes/db_config.php';

$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data pesanan
$order_sql = "SELECT p.*, u.*, pk.* 
              FROM pemesanan p
              LEFT JOIN user u ON p.id_user = u.id_user
              LEFT JOIN paket_wisata pk ON p.id_paket = pk.id_paket
              WHERE p.id_pemesanan = ?";
$order_stmt = $conn->prepare($order_sql);
$order_stmt->bind_param("i", $order_id);
$order_stmt->execute();
$order_result = $order_stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    die('Pesanan tidak ditemukan');
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #ddd;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            border-bottom: 2px solid #00AAFF;
            padding-bottom: 20px;
        }

        .company-info h1 {
            color: #00AAFF;
            margin: 0;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            color: #333;
            margin: 0;
        }

        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section h3 {
            color: #00AAFF;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background: #f5f5f5;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .total-row td {
            font-weight: bold;
            background: #f9f9f9;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        .status-lunas {
            background: #d4edda;
            color: #155724;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        @media print {
            body {
                padding: 0;
            }

            .invoice-container {
                border: none;
                padding: 0;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <h1>JAWA TRAVEL</h1>
                <p>Jl. Travel No. 123, Yogyakarta</p>
                <p>Telp: 1800-400-930 | Email: support@jawa.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p>No: #<?php echo str_pad($order_id, 6, '0', STR_PAD_LEFT); ?></p>
                <p>Tanggal: <?php echo date('d/m/Y', strtotime($order['tanggal_pemesanan'])); ?></p>
            </div>
        </div>

        <div class="content">
            <div class="section">
                <h3>Data Pemesan</h3>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($order['nama']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
                <p><strong>Telepon:</strong> <?php echo htmlspecialchars($order['nomor_telepon']); ?></p>
            </div>

            <div class="section">
                <h3>Detail Pesanan</h3>
                <p><strong>Tanggal Mulai:</strong> <?php echo date('d/m/Y', strtotime($order['tanggal_mulai'])); ?></p>
                <p><strong>Jumlah Orang:</strong> <?php echo $order['jumlah_orang']; ?> orang</p>
                <p><strong>Status:</strong>
                    <span class="status-badge status-<?php echo strtolower($order['status_pemesanan']); ?>">
                        <?php echo $order['status_pemesanan']; ?>
                    </span>
                </p>
            </div>
        </div>

        <div class="section">
            <h3>Rincian Pembayaran</h3>
            <table>
                <tr>
                    <th>Deskripsi</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
                <?php if ($order['is_custom']): ?>
                    <tr>
                        <td>Paket Kustom Wisata</td>
                        <td><?php echo $order['jumlah_orang']; ?> orang</td>
                        <td>Rp <?php echo number_format($order['total_harga'] / $order['jumlah_orang'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php else: ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['nama_paket']); ?></td>
                        <td><?php echo $order['jumlah_orang']; ?> orang</td>
                        <td>Rp <?php echo number_format($order['harga_total'], 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row">
                    <td colspan="3" style="text-align: right;"><strong>TOTAL</strong></td>
                    <td><strong>Rp <?php echo number_format($order['total_harga'], 0, ',', '.'); ?></strong></td>
                </tr>
            </table>
        </div>

        <div class="section">
            <h3>Metode Pembayaran</h3>
            <p>Transfer Bank ke:</p>
            <p><strong>Bank BCA</strong> - 1234567890 (PT. Jawa Travel Indonesia)</p>
            <p><strong>Bank Mandiri</strong> - 0987654321 (PT. Jawa Travel Indonesia)</p>
            <p><em>Harap transfer sesuai total dan konfirmasi dengan mengirim bukti transfer ke WhatsApp 081234567890</em></p>
        </div>

        <div class="footer">
            <p>Terima kasih telah memesan di Jawa Travel</p>
            <p>Invoice ini sah dan dapat digunakan sebagai bukti pembayaran</p>
            <p>www.jawa-travel.com</p>
        </div>
    </div>

    <div class="no-print" style="text-align: center; margin-top: 20px;">
        <button onclick="window.print()" style="background: #00AAFF; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;">
            🖨️ Cetak Invoice
        </button>
        <button onclick="window.close()" style="background: #666; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            ✖️ Tutup
        </button>
    </div>
</body>

</html>