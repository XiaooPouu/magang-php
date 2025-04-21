<?php
require_once __DIR__ . '/../env.php';
require_once BASE_PATH . 'config/database.php'; // database.php mengembalikan Medoo object

class Item {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Medoo instance
    }

    public function insert($ref_no, $name, $price) {
        return $this->db->insert("items", [
            "ref_no" => $ref_no,
            "name"   => $name,
            "price"  => $price
        ]);
    }

    public function getAll() {
        return $this->db->select("items", "*");
    }

    public function getById($id) {
        return $this->db->get("items", "*", ["id" => $id]);
    }

    public function getByRefNo($ref_no) {
        return $this->db->get("items", "*", ["ref_no" => $ref_no]);
    }

    public function update($id, $ref_no, $name, $price) {
        return $this->db->update("items", [
            "ref_no" => $ref_no,
            "name"   => $name,
            "price"  => $price
        ], [
            "id" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("items", ["id" => $id]);
    }

    public function search($keyword) {
        return $this->db->select("items", "*", [
            "OR" => [
                "ref_no[~]" => $keyword,
                "name[~]"   => $keyword
            ]
        ]);
    }
}
