<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';
    private $pass = '';
    private $db_name = 'db_apotek';
    public $conn;

    // Method untuk mendapatkan koneksi
    public function getConnection() {
        $this->conn = null; // Set koneksi ke null

        try {
            $this->conn = mysqli_connect($this->host, $this->user, $this->pass, $this->db_name);
        } catch (Exception $e) {
            // Seharusnya tidak menggunakan die() di production, tapi untuk development ini cukup
            die("Koneksi ke database gagal: " . $e->getMessage());
        }

        if (!$this->conn) {
            die("Koneksi ke database gagal: " . mysqli_connect_error());
        }

        return $this->conn;
    }
}
?>