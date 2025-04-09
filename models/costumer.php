<?php
require_once __DIR__ . '/../config/database.php';

class Costumer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function insert($ref_no, $name) {
        $query = "INSERT INTO customers (ref_no, name) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ss", $ref_no, $name);
        return $stmt->execute();
    }

    public function getAll() {
        $result = $this->conn->query("SELECT * FROM customers");
        return $result;
    }

    public function getById($id){
        $query = "SELECT * FROM customers WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id, $ref_no, $name) {
        $query = "UPDATE customers SET ref_no = ?, name = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $ref_no, $name, $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM customers WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
