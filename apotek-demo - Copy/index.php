<?php
// Cek autentikasi dan mulai session
include 'auth_check.php';

// --- PERUBAHAN DIMULAI DI SINI ---
// Menggunakan kelas Database untuk koneksi
include 'config/Database.php';

$database = new Database();
$connection = $database->getConnection();
// --- PERUBAHAN SELESAI ---

// Sertakan file template
include 'templates/header.php';
include 'templates/sidebar.php';

// Query untuk mengambil data statistik
$query_customer = "SELECT COUNT(id_customer) AS total FROM customer";
$result_customer = mysqli_query($connection, $query_customer);
$total_customer = mysqli_fetch_assoc($result_customer)['total'];

$query_produk = "SELECT COUNT(id_produk) AS total FROM produk";
$result_produk = mysqli_query($connection, $query_produk);
$total_produk = mysqli_fetch_assoc($result_produk)['total'];

$query_transaksi = "SELECT COUNT(id_faktur) AS total FROM faktur";
$result_transaksi = mysqli_query($connection, $query_transaksi);
$total_transaksi = mysqli_fetch_assoc($result_transaksi)['total'];

$query_pendapatan = "SELECT SUM(grand_total) AS total FROM faktur";
$result_pendapatan = mysqli_query($connection, $query_pendapatan);
// Tambahkan pengecekan jika totalnya null (belum ada transaksi)
$total_pendapatan = mysqli_fetch_assoc($result_pendapatan)['total'] ?? 0;

?>

<style>
    .dashboard-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
    .card { background-color: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card h3 { margin-top: 0; }
    .card p { font-size: 2em; font-weight: bold; margin: 0; }
</style>

<h1>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
<p>Ini adalah halaman utama aplikasi Apotek Demo.</p>

<div class="dashboard-grid">
    <div class="card" style="border-left: 5px solid #007bff;">
        <h3>Total Customer</h3>
        <p><?php echo $total_customer; ?></p>
    </div>
    <div class="card" style="border-left: 5px solid #28a745;">
        <h3>Total Produk</h3>
        <p><?php echo $total_produk; ?></p>
    </div>
    <div class="card" style="border-left: 5px solid #ffc107;">
        <h3>Total Transaksi</h3>
        <p><?php echo $total_transaksi; ?></p>
    </div>
    <div class="card" style="border-left: 5px solid #dc3545;">
        <h3>Total Pendapatan</h3>
        <p>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></p>
    </div>
</div>

<?php
include 'templates/footer.php';
?>