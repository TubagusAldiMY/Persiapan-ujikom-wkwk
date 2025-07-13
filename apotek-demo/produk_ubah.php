<?php
include 'auth_check.php';
include 'config/database.php';

$id = 0;
$nama_produk = '';
$satuan = '';
$harga = '';
$stock = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nama_produk = htmlspecialchars($_POST['nama_produk']);
    $satuan = htmlspecialchars($_POST['satuan']);
    $harga = floatval($_POST['harga']);
    $stock = intval($_POST['stock']);

    $query = "UPDATE produk SET 
                nama_produk='$nama_produk', 
                satuan='$satuan', 
                harga='$harga', 
                stock='$stock' 
              WHERE id_produk=$id";

    if (mysqli_query($connection, $query)) {
        header("Location: produk_tampil.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} 
elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM produk WHERE id_produk = $id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $nama_produk = $row['nama_produk'];
        $satuan = $row['satuan'];
        $harga = $row['harga'];
        $stock = $row['stock'];
    }
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Ubah Data Produk</h1>

<form action="produk_ubah.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <div style="margin-bottom: 15px;">
        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" id="nama_produk" name="nama_produk" required value="<?php echo $nama_produk; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="satuan">Satuan:</label><br>
        <input type="text" id="satuan" name="satuan" required value="<?php echo $satuan; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="harga">Harga:</label><br>
        <input type="number" id="harga" name="harga" required value="<?php echo $harga; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="stock">Stok:</label><br>
        <input type="number" id="stock" name="stock" required value="<?php echo $stock; ?>" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="produk_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>