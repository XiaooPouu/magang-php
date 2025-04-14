<?php
require_once __DIR__ . '/../config/config.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
class Database {
    private $conn;

    public function getConnection() {
        $this->conn = new mysqli(
            EnvLoader::get('DB_HOST'),
            EnvLoader::get('DB_USER'),
            EnvLoader::get('DB_PASS'),
            EnvLoader::get('DB_NAME')
        );

        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }

        return $this->conn;
    }
}