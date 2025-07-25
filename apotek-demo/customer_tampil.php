<?php
include 'auth_check.php';
include 'templates/header.php';
include 'templates/sidebar.php';
include 'config/database.php';
?>

<h1>Data Customer</h1>
<p>Halaman untuk mengelola data customer.</p>

<a href="customer_tambah.php" class="btn btn-primary">Tambah Data Customer</a>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Customer</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $query = "SELECT * FROM customer ORDER BY id_customer DESC";
        $result = mysqli_query($connection, $query);
        $no = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . htmlspecialchars($row['nama_customer']) . "</td>";
            echo "<td>" . htmlspecialchars($row['alamat_customer']) . "</td>";
            echo "<td>" . htmlspecialchars($row['telepon']) . "</td>";
            echo "<td>";
            echo "<a href='customer_ubah.php?id=" . $row['id_customer'] . "' class='btn btn-warning'>Ubah</a> ";
            echo "<a href='customer_hapus.php?id=" . $row['id_customer'] . "' class='btn btn-danger' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>";
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<?php
include 'templates/footer.php';
?>