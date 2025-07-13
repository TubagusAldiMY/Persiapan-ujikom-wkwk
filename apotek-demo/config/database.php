<?php
/**
 * File untuk koneksi ke database.
 */

// Konfigurasi Database
$host = 'localhost';    
$user = 'root';         
$pass = '';             
$db_name = 'db_apotek'; 

// Membuat koneksi
$connection = mysqli_connect($host, $user, $pass, $db_name);

// Cek koneksi
if (!$connection) {
    // Jika koneksi gagal, hentikan skrip dan tampilkan pesan error
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

// echo "Koneksi ke database berhasil!";
?>