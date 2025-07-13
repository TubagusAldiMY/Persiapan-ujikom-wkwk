<?php
include 'auth_check.php';
include 'config/database.php';

// Cek jika ID tidak ada di URL, redirect
if (!isset($_GET['id'])) {
    header('Location: transaksi_tampil.php');
    exit();
}

$id_faktur = intval($_GET['id']);

// Query untuk mengambil data faktur, customer, dan perusahaan
$query_faktur = "SELECT f.*, c.nama_customer, c.alamat_customer, p.nama_perusahaan, p.alamat_perusahaan, p.telepon 
                 FROM faktur f
                 JOIN customer c ON f.id_customer = c.id_customer
                 JOIN perusahaan p ON f.id_perusahaan = p.id_perusahaan
                 WHERE f.id_faktur = $id_faktur";
$result_faktur = mysqli_query($connection, $query_faktur);
$faktur = mysqli_fetch_assoc($result_faktur);

// Jika faktur tidak ditemukan, redirect
if (!$faktur) {
    header('Location: transaksi_tampil.php');
    exit();
}

// Query untuk mengambil item-item produk dalam faktur
$query_items = "SELECT d.*, p.nama_produk, p.satuan 
                FROM detail_faktur d
                JOIN produk p ON d.id_produk = p.id_produk
                WHERE d.id_faktur = $id_faktur";
$result_items = mysqli_query($connection, $query_items);

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Faktur <?php echo htmlspecialchars($faktur['no_faktur']); ?></title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        .customer-details, .invoice-details { font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid #ddd; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .totals { float: right; width: 250px; margin-top: 20px; }
        .totals table { border: none; }
        .totals td { border: none; padding: 5px 8px; }
        .text-right { text-align: right; }
        @media print {
            body, .invoice-box { margin: 0; padding: 0; border: none; box-shadow: none; }
            .no-print { display: none; }
        }
        .btn-print { padding: 10px 20px; font-size: 16px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="header no-print">
        <button onclick="window.print()" class="btn-print">Cetak Faktur</button>
    </div>
    <div class="invoice-box">
        <h2><?php echo htmlspecialchars($faktur['nama_perusahaan']); ?></h2>
        <p><?php echo htmlspecialchars($faktur['alamat_perusahaan']); ?><br>
           Telp: <?php echo htmlspecialchars($faktur['telepon']); ?></p>
        <hr>
        <h1 style="text-align: center;">FAKTUR</h1>
        
        <div class="details-grid">
            <div class="customer-details">
                <strong>Kepada Yth:</strong><br>
                <?php echo htmlspecialchars($faktur['nama_customer']); ?><br>
                <?php echo nl2br(htmlspecialchars($faktur['alamat_customer'])); ?>
            </div>
            <div class="invoice-details">
                <strong>No. Faktur:</strong> <?php echo htmlspecialchars($faktur['no_faktur']); ?><br>
                <strong>Tanggal:</strong> <?php echo date('d M Y', strtotime($faktur['tgl_faktur'])); ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                while($item = mysqli_fetch_assoc($result_items)): ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo htmlspecialchars($item['nama_produk']); ?></td>
                    <td><?php echo htmlspecialchars($item['satuan']); ?></td>
                    <td class="text-right">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                    <td class="text-right"><?php echo $item['qty']; ?></td>
                    <td class="text-right">Rp <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td><strong>Total</strong></td>
                    <td class="text-right"><strong>Rp <?php echo number_format($faktur['grand_total'], 0, ',', '.'); ?></strong></td>
                </tr>
                <tr>
                    <td>Bayar</td>
                    <td class="text-right">Rp <?php echo number_format($faktur['bayar'], 0, ',', '.'); ?></td>
                </tr>
                <tr>
                    <td>Kembali</td>
                    <td class="text-right">Rp <?php echo number_format($faktur['kembali'], 0, ',', '.'); ?></td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>