<?php
require_once __DIR__ . '/../config/database.php';

class Item {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert($ref_no, $name, $price) {
        $query = "INSERT INTO items (ref_no, name, price) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssd", $ref_no, $name, $price);
        return $stmt->execute();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM items");
        return $result;
    }

    public function getById($id) {
        $query = "SELECT * FROM items WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getByRefNo($ref_no) {
        $stmt = $this->conn->prepare("SELECT * FROM items WHERE ref_no = ?");
    $stmt->bind_param("s", $ref_no);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc(); // Kembalikan 1 baris data
    }

    public function update($id, $ref_no, $name, $price) {
        $query = "UPDATE items SET ref_no = ?, name = ?, price = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssdi", $ref_no, $name, $price, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM items WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
