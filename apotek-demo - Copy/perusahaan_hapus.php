<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Perusahaan.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $perusahaan = new Perusahaan($db);

    $perusahaan->id_perusahaan = $_GET['id'];

    if ($perusahaan->delete()) {
        header("Location: perusahaan_tampil.php");
        exit();
    } else {
        die("Error saat menghapus data.");
    }
} else {
    header("Location: perusahaan_tampil.php");
    exit();
}
?>