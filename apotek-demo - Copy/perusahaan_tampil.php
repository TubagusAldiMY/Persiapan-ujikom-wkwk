<?php
// Masukkan file yang diperlukan
include 'auth_check.php';
include 'config/Database.php';
include 'models/Perusahaan.php';
include 'templates/header.php';
include 'templates/sidebar.php';

// Inisialisasi objek
$database = new Database();
$db = $database->getConnection();
$perusahaan = new Perusahaan($db);

// Ambil data perusahaan
$result = $perusahaan->read();
?>

<h1>Data Perusahaan</h1>
<p>Ini adalah halaman untuk mengelola data perusahaan.</p>

<a href="perusahaan_tambah.php" class="btn btn-primary">Tambah Data Perusahaan</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Perusahaan</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $no = 1; 
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_perusahaan']) . "</td>";
            echo "<td>" . htmlspecialchars($row['alamat_perusahaan']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telepon']) . "</td>";
            echo "<td>";
            echo "<a href='perusahaan_ubah.php?id=" . $row['id_perusahaan'] . "' class='btn btn-warning'>Ubah</a> ";
            echo "<a href='perusahaan_hapus.php?id=" . $row['id_perusahaan'] . "' class='btn btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>