<?php
require_once __DIR__ . '/../config/database.php';

class ItemsCostumer {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Ambil semua data item-customer dengan JOIN ke nama item dan customer
    public function getAll() {
        $sql = "SELECT 
                    ic.id_ic,
                    i.name AS items_name,
                    c.name AS customers_name,
                    ic.price
                FROM items_customers ic
                JOIN items i ON ic.id_items = i.id
                JOIN customers c ON ic.id_customers = c.id";
                
        $result = $this->conn->query($sql);
        $data = [];

        // Ubah hasil query menjadi array asosiatif
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getById($id_ic){
        $sql = "SELECT * FROM items_customers WHERE id_ic = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_ic);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update($id_ic, $item_id, $customer_id, $price){
        $sql = "UPDATE items_customers SET id_items = ?, id_customers = ?, price = ? WHERE id_ic = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iidi", $item_id, $customer_id, $price, $id_ic);
        return $stmt->execute();
    }

    // Menyimpan data baru
    public function insert($item_id, $customer_id, $price) {
        $sql = "INSERT INTO items_customers (id_items, id_customers, price) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iid", $item_id, $customer_id, $price);
        return $stmt->execute();
    }

    public function delete($id_ic) {
        $sql = "DELETE FROM items_customers WHERE id_ic = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_ic);
        return $stmt->execute();
    }

    public function search($keyword){
        $keyword = "%{$keyword}%";
        $sql = "SELECT 
                    ic.id_ic,
                    i.name AS items_name,
                    c.name AS customers_name,
                    ic.price
                FROM items_customers ic
                JOIN items i ON ic.id_items = i.id
                JOIN customers c ON ic.id_customers = c.id
                WHERE i.name LIKE ? OR c.name LIKE ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss" , $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $data = [];

        // Ubah hasil query menjadi array asosiatif
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }
}
