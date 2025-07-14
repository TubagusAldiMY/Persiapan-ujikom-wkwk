<?php
include 'auth_check.php';
include 'config/Database.php';
include 'models/Customer.php';

if (isset($_GET['id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $customer = new Customer($db);

    $customer->id_customer = $_GET['id'];

    if ($customer->delete()) {
        header("Location: customer_tampil.php");
        exit();
    } else {
        die("Error saat menghapus data.");
    }
} else {
    header("Location: customer_tampil.php");
    exit();
}
?>