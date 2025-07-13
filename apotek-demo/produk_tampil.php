<?php
include 'auth_check.php';
include 'templates/header.php';
include 'templates/sidebar.php';
include 'config/database.php';
?>

<h1>Data Produk</h1>
<p>Halaman untuk mengelola data produk atau obat.</p>

<a href="produk_tambah.php" class="btn btn-primary">Tambah Data Produk</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Produk</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM produk ORDER BY id_produk DESC";
        $result = mysqli_query($connection, $query);
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
            echo "<td>" . htmlspecialchars($row['satuan']) . "</td>";
            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
            echo "<td>";
            echo "<a href='produk_ubah.php?id=" . $row['id_produk'] . "' class='btn btn-warning'>Ubah</a> ";
            echo "<a href='produk_hapus.php?id=" . $row['id_produk'] . "' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus produk ini?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>