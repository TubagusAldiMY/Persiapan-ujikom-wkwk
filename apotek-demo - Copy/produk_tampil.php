<?php
include 'auth_check.php';

// Memanggil file kelas yang dibutuhkan
include 'config/Database.php';
include 'models/Produk.php';
include 'templates/header.php';
include 'templates/sidebar.php';

// Inisialisasi objek database dan mendapatkan koneksi
$database = new Database();
$db = $database->getConnection();

// Inisialisasi objek produk
$produk = new Produk($db);

// Mendapatkan data produk dari method read()
$result = $produk->read();
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
        $no = 1;
        // Loop melalui hasil data yang sudah didapat dari objek produk
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_produk']) . "</td>";
            echo "<td>" . htmlspecialchars($row['satuan']) . "</td>";
            echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
            echo "<td>" . htmlspecialchars($row['stock']) . "</td>";
            echo "<td>";
            // Link ke halaman ubah dan hapus tetap sama untuk saat ini
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