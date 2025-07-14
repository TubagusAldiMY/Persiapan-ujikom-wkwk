<?php
include 'auth_check.php';
include 'config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM produk WHERE id_produk = $id";

    if (mysqli_query($connection, $query)) {
        header("Location: produk_tampil.php");
        exit();
    } else {
        die("Error saat menghapus data: " . mysqli_error($connection));
    }
} else {
    header("Location: produk_tampil.php");
    exit();
}
?>