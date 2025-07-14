<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Perusahaan.php';

$database = new Database();
$db = $database->getConnection();
$perusahaan = new Perusahaan($db);

// Jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set properti objek
    $perusahaan->nama_perusahaan = $_POST['nama_perusahaan'];
    $perusahaan->alamat_perusahaan = $_POST['alamat_perusahaan'];
    $perusahaan->telepon = $_POST['telepon'];
    $perusahaan->fax = $_POST['fax'];

    // Panggil method create
    if ($perusahaan->create()) {
        header("Location: perusahaan_tampil.php");
        exit();
    } else {
        echo "Error: Gagal menyimpan data.";
    }
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Tambah Data Perusahaan Baru</h1>

<form action="perusahaan_tambah.php" method="POST">
    <div style="margin-bottom: 15px;">
        <label for="nama_perusahaan">Nama Perusahaan:</label><br>
        <input type="text" id="nama_perusahaan" name="nama_perusahaan" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_perusahaan">Alamat:</label><br>
        <textarea id="alamat_perusahaan" name="alamat_perusahaan" required style="width: 100%; padding: 8px;" rows="4"></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="fax">Fax (Opsional):</label><br>
        <input type="text" id="fax" name="fax" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="perusahaan_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>