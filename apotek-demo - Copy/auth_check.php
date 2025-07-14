<?php
session_start();
// Jika tidak ada session id_user, artinya belum login
if (!isset($_SESSION['id_user'])) {
    // Redirect ke halaman login
    header('Location: login.php');
    exit();
}
?>