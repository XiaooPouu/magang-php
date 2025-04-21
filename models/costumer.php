<?php
require_once __DIR__ . '/../env.php';
require_once BASE_PATH . 'config/database.php'; // pastikan file ini mengembalikan instance Medoo

class Costumer {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Medoo instance
    }

    public function insert($ref_no, $name) {
        return $this->db->insert("customers", [
            "ref_no" => $ref_no,
            "name"   => $name
        ]);
    }

    public function getAll() {
        return $this->db->select("customers", "*");
    }

    public function getByRefNo($ref_no) {
        return $this->db->get("customers", "*", ["ref_no" => $ref_no]);
    }

    public function getById($id) {
        return $this->db->get("customers", "*", ["id" => $id]);
    }

    public function update($id, $ref_no, $name) {
        return $this->db->update("customers", [
            "ref_no" => $ref_no,
            "name"   => $name
        ], [
            "id" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("customers", ["id" => $id]);
    }

    public function search($keyword) {
        return $this->db->select("customers", "*", [
            "OR" => [
                "ref_no[~]" => $keyword,
                "name[~]"   => $keyword
            ]
        ]);
    }
}
