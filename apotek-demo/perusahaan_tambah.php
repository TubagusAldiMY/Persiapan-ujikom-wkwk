<?php
// Sertakan file koneksi database
include 'auth_check.php';
include 'config/database.php';

// Cek apakah form telah disubmit (method POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan bersihkan dari potensi script jahat
    $nama_perusahaan = htmlspecialchars($_POST['nama_perusahaan']);
    $alamat_perusahaan = htmlspecialchars($_POST['alamat_perusahaan']);
    $telepon = htmlspecialchars($_POST['telepon']);
    $fax = htmlspecialchars($_POST['fax']);

    // Siapkan query SQL untuk memasukkan data baru
    $query = "INSERT INTO perusahaan (nama_perusahaan, alamat_perusahaan, telepon, fax) 
              VALUES ('$nama_perusahaan', '$alamat_perusahaan', '$telepon', '$fax')";

    // Eksekusi query
    if (mysqli_query($connection, $query)) {
        // Jika berhasil, redirect ke halaman utama data perusahaan
        header("Location: perusahaan_tampil.php");
        exit(); // Hentikan eksekusi skrip setelah redirect
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Error: " . $query . "<br>" . mysqli_error($connection);
    }
}

// Sertakan file header dan sidebar
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
// Sertakan file footer
include 'templates/footer.php';
?>