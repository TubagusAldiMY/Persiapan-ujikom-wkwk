<?php
class Customer {
    private $conn;
    private $table_name = "customer";

    // Properti Customer
    public $id_customer;
    public $nama_customer;
    public $alamat_customer;
    public $telepon;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk membaca semua data customer
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_customer DESC";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    // Method untuk membaca satu data customer berdasarkan ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_customer = ? LIMIT 0,1";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $this->id_customer);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)) {
            $this->nama_customer = $row['nama_customer'];
            $this->alamat_customer = $row['alamat_customer'];
            $this->telepon = $row['telepon'];
        }
    }

    // Method untuk membuat customer baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nama_customer, alamat_customer, telepon) VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($this->conn, $query);

        // Membersihkan data
        $this->nama_customer = htmlspecialchars(strip_tags($this->nama_customer));
        $this->alamat_customer = htmlspecialchars(strip_tags($this->alamat_customer));
        $this->telepon = htmlspecialchars(strip_tags($this->telepon));

        mysqli_stmt_bind_param($stmt, "sss", $this->nama_customer, $this->alamat_customer, $this->telepon);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }

    // Method untuk mengubah data customer
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_customer = ?, alamat_customer = ?, telepon = ? WHERE id_customer = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);

        // Membersihkan data
        $this->nama_customer = htmlspecialchars(strip_tags($this->nama_customer));
        $this->alamat_customer = htmlspecialchars(strip_tags($this->alamat_customer));
        $this->telepon = htmlspecialchars(strip_tags($this->telepon));
        $this->id_customer = intval($this->id_customer);

        mysqli_stmt_bind_param($stmt, "sssi", $this->nama_customer, $this->alamat_customer, $this->telepon, $this->id_customer);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }

    // Method untuk menghapus data customer
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_customer = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        
        $this->id_customer = intval($this->id_customer);
        
        mysqli_stmt_bind_param($stmt, "i", $this->id_customer);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }
}
?>