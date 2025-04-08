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
