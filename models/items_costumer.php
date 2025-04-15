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

    // Menyimpan data baru
    public function insert($item_id, $customer_id, $price) {
        $sql = "INSERT INTO items_customers (id_items, id_customers, price) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("iid", $item_id, $customer_id, $price);
        return $stmt->execute();
    }
}
