<?php
include 'auth_check.php';
include 'config/database.php';

$id = 0;
$nama_customer = '';
$alamat_customer = '';
$telepon = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $nama_customer = htmlspecialchars($_POST['nama_customer']);
    $alamat_customer = htmlspecialchars($_POST['alamat_customer']);
    $telepon = htmlspecialchars($_POST['telepon']);

    $query = "UPDATE customer SET 
                nama_customer='$nama_customer', 
                alamat_customer='$alamat_customer', 
                telepon='$telepon'
              WHERE id_customer=$id";

    if (mysqli_query($connection, $query)) {
        header("Location: customer_tampil.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($connection);
    }
} 
elseif (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM customer WHERE id_customer = $id";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $nama_customer = $row['nama_customer'];
        $alamat_customer = $row['alamat_customer'];
        $telepon = $row['telepon'];
    }
}

include 'templates/header.php';
include 'templates/sidebar.php';
?>

<h1>Ubah Data Customer</h1>

<form action="customer_ubah.php" method="POST">
    <input type="hidden" name="id" value="<?php echo $id; ?>">
    
    <div style="margin-bottom: 15px;">
        <label for="nama_customer">Nama Customer:</label><br>
        <input type="text" id="nama_customer" name="nama_customer" required value="<?php echo $nama_customer; ?>" style="width: 100%; padding: 8px;">
    </div>
    <div style="margin-bottom: 15px;">
        <label for="alamat_customer">Alamat:</label><br>
        <textarea id="alamat_customer" name="alamat_customer" required style="width: 100%; padding: 8px;" rows="4"><?php echo $alamat_customer; ?></textarea>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="telepon">Telepon:</label><br>
        <input type="text" id="telepon" name="telepon" required value="<?php echo $telepon; ?>" style="width: 100%; padding: 8px;">
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
    <a href="customer_tampil.php" class="btn btn-secondary">Batal</a>
</form>

<?php
include 'templates/footer.php';
?>