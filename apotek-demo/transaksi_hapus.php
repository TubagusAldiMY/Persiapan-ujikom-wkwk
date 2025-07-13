<?php
include 'auth_check.php';
include 'config/database.php';

if (isset($_GET['id'])) {
    $id_faktur = intval($_GET['id']);

    // Mulai transaksi database untuk memastikan semua query berhasil
    mysqli_begin_transaction($connection);

    try {
        // 1. Ambil semua item detail dari faktur yang akan dihapus
        $query_detail = "SELECT id_produk, qty FROM detail_faktur WHERE id_faktur = $id_faktur";
        $result_detail = mysqli_query($connection, $query_detail);

        // 2. Kembalikan stok untuk setiap produk
        while ($item = mysqli_fetch_assoc($result_detail)) {
            $id_produk = $item['id_produk'];
            $qty = $item['qty'];
            $query_update_stok = "UPDATE produk SET stock = stock + $qty WHERE id_produk = $id_produk";
            mysqli_query($connection, $query_update_stok);
        }

        // 3. Hapus data dari tabel detail_faktur
        $query_delete_detail = "DELETE FROM detail_faktur WHERE id_faktur = $id_faktur";
        mysqli_query($connection, $query_delete_detail);

        // 4. Hapus data dari tabel faktur utama
        $query_delete_faktur = "DELETE FROM faktur WHERE id_faktur = $id_faktur";
        mysqli_query($connection, $query_delete_faktur);

        // Jika semua query berhasil, commit transaksi
        mysqli_commit($connection);

        // Redirect kembali ke halaman daftar transaksi
        header("Location: transaksi_tampil.php");
        exit();

    } catch (mysqli_sql_exception $exception) {
        // Jika ada error, batalkan semua perubahan (rollback)
        mysqli_rollback($connection);
        die("Gagal menghapus transaksi: " . $exception->getMessage());
    }
}
?>