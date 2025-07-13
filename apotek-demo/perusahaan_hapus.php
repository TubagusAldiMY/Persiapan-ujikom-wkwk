<?php
// Sertakan file koneksi database
include 'auth_check.php';
include 'config/database.php';

// Cek apakah 'id' ada di URL
if (isset($_GET['id'])) {
    // Ambil id dari URL dan pastikan itu adalah angka
    $id = intval($_GET['id']);

    // Buat query untuk menghapus data
    $query = "DELETE FROM perusahaan WHERE id_perusahaan = $id";

    // Eksekusi query
    if (mysqli_query($connection, $query)) {
        // Jika berhasil, redirect kembali ke halaman daftar
        header("Location: perusahaan_tampil.php");
        exit();
    } else {
        // Jika gagal, tampilkan error
        die("Error saat menghapus data: " . mysqli_error($connection));
    }
} else {
    // Jika tidak ada id di URL, redirect ke halaman utama
    header("Location: perusahaan_tampil.php");
    exit();
}
?>