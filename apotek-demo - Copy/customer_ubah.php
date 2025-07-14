<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Customer.php';

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);

// Cek jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer->id_customer = $_POST['id'];
    $customer->nama_customer = $_POST['nama_customer'];
    $customer->alamat_customer = $_POST['alamat_customer'];
    $customer->telepon = $_POST['telepon'];

    if ($customer->update()) {
        header("Location: customer_tampil.php");
        exit();
    } else {
        echo "Error: Gagal mengubah data.";
    }
} 
// Ambil data yang akan diubah berdasarkan ID dari URL
elseif (isset($_GET['id'])) {
    $customer->id_customer = $_GET['id'];
    $customer->readOne();
}
?>

<?php include 'templates/header.php'; ?>
<?php include 'templates/sidebar.php'; ?>

<h1>Ubah Data Customer</h1>

<form action="customer_ubah.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $customer->id_customer; ?>">
    
    <div style="margin-bottom: 15px;">
        <label for="nama_customer">Nama Customer:</label><br>
        <input type="text" id="nama_customer" name="nama_customer" required value="<?php echo $customer->nama_customer; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_customer">Alamat:</label><br>
        <textarea id="alamat_customer" name="alamat_customer" required style="width: 100%; padding: 8px;" rows="4"><?php echo $customer->alamat_customer; ?></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required value="<?php echo $customer->telepon; ?>" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="customer_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php include 'templates/footer.php'; ?>