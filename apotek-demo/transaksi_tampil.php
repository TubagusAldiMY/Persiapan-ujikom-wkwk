<?php
include 'auth_check.php';
include 'templates/header.php';
include 'templates/sidebar.php';
include 'config/database.php';
?>

<h1>Data Transaksi Penjualan</h1>
<p>Halaman untuk mengelola data penjualan.</p>

<a href="transaksi_tambah.php" class="btn btn-primary">Buat Transaksi Baru</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>No. Faktur</th>
            <th>Tanggal</th>
            <th>Nama Customer</th>
            <th>Grand Total</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Query untuk mengambil data faktur dan nama customer
        $query = "SELECT faktur.*, customer.nama_customer 
                  FROM faktur 
                  JOIN customer ON faktur.id_customer = customer.id_customer
                  ORDER BY faktur.tgl_faktur DESC";
                  
        $result = mysqli_query($connection, $query);
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['no_faktur']) . "</td>";
            echo "<td>" . date('d-m-Y', strtotime($row['tgl_faktur'])) . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_customer']) . "</td>";
            echo "<td>Rp " . number_format($row['grand_total'], 0, ',', '.') . "</td>";
            echo "<td>";
            echo "<a href='transaksi_cetak.php?id=" . $row['id_faktur'] . "' class='btn btn-secondary' target='_blank'>Cetak</a> ";
            echo "<a href='transaksi_hapus.php?id=" . $row['id_faktur'] . "' class='btn btn-danger' onclick='return confirm(\"Menghapus transaksi ini akan mengembalikan stok produk. Yakin?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>