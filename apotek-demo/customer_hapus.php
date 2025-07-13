<?php
include 'auth_check.php';
include 'config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM customer WHERE id_customer = $id";

    if (mysqli_query($connection, $query)) {
        header("Location: customer_tampil.php");
        exit();
    } else {
        die("Error saat menghapus data: " . mysqli_error($connection));
    }
} else {
    header("Location: customer_tampil.php");
    exit();
}
?>