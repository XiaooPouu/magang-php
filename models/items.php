<?php

require_once '../config/database.php';

class Item {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function all() {
        $stmt = $this->pdo->query("SELECT * FROM items");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($ref_no, $name, $price) {
        $stmt = $this->pdo->prepare("INSERT INTO items (ref_no, name, price) VALUES (?, ?, ?)");
        return $stmt->execute([$ref_no, $name, $price]);
    }

    public function find($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM items WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $ref_no, $name, $price) {
        $stmt = $this->pdo->prepare("UPDATE items SET ref_no = ?, name = ?, price = ? WHERE id = ?");
        return $stmt->execute([$ref_no, $name, $price, $id]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM items WHERE id = ?");
        return $stmt->execute([$id]);
    }
}