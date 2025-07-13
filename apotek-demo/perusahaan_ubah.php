<?php
include 'auth_check.php';
include 'config/database.php';

$id = 0;
$nama_perusahaan = '';
$alamat_perusahaan = '';
$telepon = '';
$fax = '';

// Cek jika form disubmit (Update)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nama_perusahaan = htmlspecialchars($_POST['nama_perusahaan']);
    $alamat_perusahaan = htmlspecialchars($_POST['alamat_perusahaan']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $fax = htmlspecialchars($_POST['fax']);

    $query = "UPDATE perusahaan SET 
                nama_perusahaan='$nama_perusahaan', 
                alamat_perusahaan='$alamat_perusahaan', 
                telepon='$telepon', 
                fax='$fax' 
              WHERE id_perusahaan=$id";

    if (mysqli_query($connection, $query)) {
        header("Location: perusahaan_tampil.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} 
// Cek jika ada ID di URL (Tampilkan form untuk diubah)
elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM perusahaan WHERE id_perusahaan = $id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $nama_perusahaan = $row['nama_perusahaan'];
        $alamat_perusahaan = $row['alamat_perusahaan'];
        $telepon = $row['telepon'];
        $fax = $row['fax'];
    }
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Ubah Data Perusahaan</h1>

<form action="perusahaan_ubah.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <div style="margin-bottom: 15px;">
        <label for="nama_perusahaan">Nama Perusahaan:</label><br>
        <input type="text" id="nama_perusahaan" name="nama_perusahaan" required value="<?php echo $nama_perusahaan; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_perusahaan">Alamat:</label><br>
        <textarea id="alamat_perusahaan" name="alamat_perusahaan" required style="width: 100%; padding: 8px;" rows="4"><?php echo $alamat_perusahaan; ?></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required value="<?php echo $telepon; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="fax">Fax (Opsional):</label><br>
        <input type="text" id="fax" name="fax" value="<?php echo $fax; ?>" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="perusahaan_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>