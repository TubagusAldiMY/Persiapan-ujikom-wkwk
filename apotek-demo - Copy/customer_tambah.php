<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Customer.php';

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);

// Proses form jika di-submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set properti objek customer
    $customer->nama_customer = $_POST['nama_customer'];
    $customer->alamat_customer = $_POST['alamat_customer'];
    $customer->telepon = $_POST['telepon'];

    // Panggil method create
    if ($customer->create()) {
        header("Location: customer_tampil.php");
        exit();
    } else {
        echo "Error: Gagal menyimpan data.";
    }
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Tambah Data Customer Baru</h1>

<form action="customer_tambah.php" method="POST">
    <div style="margin-bottom: 15px;">
        <label for="nama_customer">Nama Customer:</label><br>
        <input type="text" id="nama_customer" name="nama_customer" required style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_customer">Alamat:</label><br>
        <textarea id="alamat_customer" name="alamat_customer" required style="width: 100%; padding: 8px;" rows="4"></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required style="width: 100%; padding: 8px;">
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
    <a href="customer_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>