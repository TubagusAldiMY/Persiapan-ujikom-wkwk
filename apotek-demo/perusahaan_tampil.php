<?php
// Masukkan file header, sidebar, dan koneksi database
include 'auth_check.php';
include 'templates/header.php';
include 'templates/sidebar.php';
include 'config/database.php';
?>

<h1_>Data Perusahaan</h1>
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
        // Query untuk mengambil semua data dari tabel perusahaan
        $query = "SELECT * FROM perusahaan ORDER BY id_perusahaan DESC";
        $result = mysqli_query($connection, $query);
        
        // Cek jika query berhasil
        if (!$result) {
            die("Query gagal: " . mysqli_error($connection));
        }

        $no = 1; // Variabel untuk nomor urut
        // Looping untuk menampilkan data
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
// Masukkan file footer
include 'templates/footer.php';
?>