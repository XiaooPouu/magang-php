<?php
include '../includes/header.php';
class Database {
    private $host = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "magang_php"; 
    private $conn;

    public function getConnection() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}