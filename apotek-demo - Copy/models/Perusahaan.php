<?php
class Perusahaan {
    private $conn;
    private $table_name = "perusahaan";

    // Properti Perusahaan
    public $id_perusahaan;
    public $nama_perusahaan;
    public $alamat_perusahaan;
    public $telepon;
    public $fax;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Method untuk membaca semua data perusahaan
    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id_perusahaan DESC";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }

    // Method untuk membaca satu data perusahaan berdasarkan ID
    public function readOne() {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id_perusahaan = ? LIMIT 0,1";
        
        $stmt = mysqli_prepare($this->conn, $query);
        mysqli_stmt_bind_param($stmt, "i", $this->id_perusahaan);
        mysqli_stmt_execute($stmt);
        
        $result = mysqli_stmt_get_result($stmt);
        if($row = mysqli_fetch_assoc($result)) {
            $this->nama_perusahaan = $row['nama_perusahaan'];
            $this->alamat_perusahaan = $row['alamat_perusahaan'];
            $this->telepon = $row['telepon'];
            $this->fax = $row['fax'];
        }
    }

    // Method untuk membuat data perusahaan baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nama_perusahaan, alamat_perusahaan, telepon, fax) VALUES (?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($this->conn, $query);

        // Membersihkan data
        $this->nama_perusahaan = htmlspecialchars(strip_tags($this->nama_perusahaan));
        $this->alamat_perusahaan = htmlspecialchars(strip_tags($this->alamat_perusahaan));
        $this->telepon = htmlspecialchars(strip_tags($this->telepon));
        $this->fax = htmlspecialchars(strip_tags($this->fax));

        mysqli_stmt_bind_param($stmt, "ssss", $this->nama_perusahaan, $this->alamat_perusahaan, $this->telepon, $this->fax);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }

    // Method untuk mengubah data perusahaan
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_perusahaan = ?, alamat_perusahaan = ?, telepon = ?, fax = ? WHERE id_perusahaan = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);

        // Membersihkan data
        $this->nama_perusahaan = htmlspecialchars(strip_tags($this->nama_perusahaan));
        $this->alamat_perusahaan = htmlspecialchars(strip_tags($this->alamat_perusahaan));
        $this->telepon = htmlspecialchars(strip_tags($this->telepon));
        $this->fax = htmlspecialchars(strip_tags($this->fax));
        $this->id_perusahaan = intval($this->id_perusahaan);

        mysqli_stmt_bind_param($stmt, "ssssi", $this->nama_perusahaan, $this->alamat_perusahaan, $this->telepon, $this->fax, $this->id_perusahaan);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }

    // Method untuk menghapus data perusahaan
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_perusahaan = ?";
        
        $stmt = mysqli_prepare($this->conn, $query);
        
        $this->id_perusahaan = intval($this->id_perusahaan);
        
        mysqli_stmt_bind_param($stmt, "i", $this->id_perusahaan);

        if (mysqli_stmt_execute($stmt)) {
            return true;
        }
        return false;
    }
}
?>