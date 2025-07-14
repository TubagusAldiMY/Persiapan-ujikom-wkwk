<?php
class Produk {
    private $conn;
    private $table_name = "produk";

    // Properti Produk
    public $id_produk;
    public $nama_produk;
    public $satuan;
    public $harga;
    public $stock;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk membaca semua produk
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_produk DESC";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    // Method untuk menambah produk baru
    // Lebih aman menggunakan prepared statements untuk mencegah SQL Injection
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nama_produk, satuan, harga, stock) VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($this->conn, $query);

        // Membersihkan data (sanitize)
        $this->nama_produk = htmlspecialchars(strip_tags($this->nama_produk));
        $this->satuan = htmlspecialchars(strip_tags($this->satuan));
        $this->harga = floatval($this->harga);
        $this->stock = intval($this->stock);

        mysqli_stmt_bind_param($stmt, "ssdi", $this->nama_produk, $this->satuan, $this->harga, $this->stock);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }
    
    // Method lain seperti update() dan delete() bisa ditambahkan dengan pola yang sama.
}
?>