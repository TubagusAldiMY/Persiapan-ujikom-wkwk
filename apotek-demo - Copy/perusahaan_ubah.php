<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Perusahaan.php';

$database = new Database();
$db = $database->getConnection();
$perusahaan = new Perusahaan($db);

// Proses update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $perusahaan->id_perusahaan = $_POST['id'];
    $perusahaan->nama_perusahaan = $_POST['nama_perusahaan'];
    $perusahaan->alamat_perusahaan = $_POST['alamat_perusahaan'];
    $perusahaan->telepon = $_POST['telepon'];
    $perusahaan->fax = $_POST['fax'];

    if ($perusahaan->update()) {
        header("Location: perusahaan_tampil.php");
        exit();
    } else {
        echo "Error: Gagal mengubah data.";
    }
} 
// Ambil data untuk ditampilkan di form
elseif (isset($_GET['id'])) {
    $perusahaan->id_perusahaan = $_GET['id'];
    $perusahaan->readOne();
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Ubah Data Perusahaan</h1>

<form action="perusahaan_ubah.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $perusahaan->id_perusahaan; ?>">
    
    <div style="margin-bottom: 15px;">
        <label for="nama_perusahaan">Nama Perusahaan:</label><br>
        <input type="text" id="nama_perusahaan" name="nama_perusahaan" required value="<?php echo $perusahaan->nama_perusahaan; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_perusahaan">Alamat:</label><br>
        <textarea id="alamat_perusahaan" name="alamat_perusahaan" required style="width: 100%; padding: 8px;" rows="4"><?php echo $perusahaan->alamat_perusahaan; ?></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required value="<?php echo $perusahaan->telepon; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="fax">Fax (Opsional):</label><br>
        <input type="text" id="fax" name="fax" value="<?php echo $perusahaan->fax; ?>" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="perusahaan_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>