<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Produk.php';

// Inisialisasi objek
$database = new Database();
$db = $database->getConnection();
$produk = new Produk($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengisi properti objek produk dari form
    $produk->nama_produk = $_POST['nama_produk'];
    $produk->satuan = $_POST['satuan'];
    $produk->harga = $_POST['harga'];
    $produk->stock = $_POST['stock'];

    // Memanggil method create()
    if ($produk->create()) {
        header("Location: produk_tampil.php");
        exit();
    } else {
        echo "Error: Gagal menyimpan data produk.";
    }
}

// Bagian tampilan tetap sama
include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Tambah Data Produk Baru</h1>

<form action="produk_tambah.php" method="POST">
    <div style="margin-bottom: 15px;">
        <label for="nama_produk">Nama Produk:</label><br>
        <input type="text" id="nama_produk" name="nama_produk" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="satuan">Satuan (e.g., Strip, Botol, Box):</label><br>
        <input type="text" id="satuan" name="satuan" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="harga">Harga:</label><br>
        <input type="number" id="harga" name="harga" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="stock">Stok:</label><br>
        <input type="number" id="stock" name="stock" required style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="produk_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>