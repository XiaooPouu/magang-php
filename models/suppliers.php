<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php'; // pastikan file ini return instance Medoo

class Supplier {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Medoo instance
    }

    public function insert($ref_no, $name) {
        return $this->db->insert("suppliers", [
            "ref_no" => $ref_no,
            "name"   => $name
        ]);
    }

    public function getAll() {
        return $this->db->select("suppliers", "*");
    }

    public function getCount() {
        return $this->db->count("suppliers");
    }

    public function getWithLimit($limit, $offset) {
        return $this->db->select("suppliers", "*", [
            "LIMIT" => [$offset, $limit],
            "ORDER" => ["id" => "DESC"]
        ]);
    }

    public function getByRefNo($ref_no, $id) {
        return $this->db->has("suppliers", [
            "ref_no" => $ref_no,
            "id[!]" => $id
        ]);
    }

    public function getById($id) {
        return $this->db->get("suppliers", "*", ["id" => $id]);
    }

    public function update($id, $ref_no, $name) {
        return $this->db->update("suppliers", [
            "ref_no" => $ref_no,
            "name"   => $name
        ], [
            "id" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("suppliers", ["id" => $id]);
    }

    public function search($keyword, $offset = null, $limit = null) {
        $where = [
            "LIMIT" => [$offset, $limit],
            "ORDER" => ["id" => "DESC"]
        ];

        if(!empty($keyword)){
            $where["OR"] = [
                "ref_no[~]" => $keyword,
                "name[~]"   => $keyword
            ];
        }

        return $this->db->select("suppliers", "*", $where);
    }
}

$supplierModel = new Supplier($db);
