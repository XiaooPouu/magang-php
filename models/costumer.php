<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php'; // pastikan file ini mengembalikan instance Medoo

class Costumer {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Medoo instance
    }

    public function insert($ref_no, $name, $alamat, $nomer, $email) {
        return $this->db->insert("customers", [
            "ref_no" => $ref_no,
            "name"   => $name,
            "alamat" => $alamat,
            "nomer" => $nomer,
            "email" => $email
        ]);
    }

    public function getAll() {
        return $this->db->select("customers", "*");
    }

    public function getAllNoID(){
        return $this->db->select("customers", [
            "ref_no",
            "name",
            "alamat",
            "nomer",
            "email"
        ]);
    }

    public function getByRefNo($ref_no, $id = null) {
        return $this->db->has("customers",[
            "ref_no" => $ref_no,
            "id[!]" => $id
        ]);
    }

    public function getCount() {
        return $this->db->count("customers");
    }

    public function updateByKode($name, $alamat, $nomer, $email,$ref_no) {
        return $this->db->update("customers", [
            "name"   => $name,
            "alamat" => $alamat,
            "nomer" => $nomer,
            "email" => $email
        ], ["ref_no" => $ref_no]);
    }

    public function insertData($ref_no, $name, $alamat, $nomer, $email) {
        return $this->db->insert("customers", [
            "ref_no" => $ref_no,
            "name"   => $name,
            "alamat" => $alamat,
            "nomer" => $nomer,
            "email" => $email
        ]);
    }

    public function getWhitLimit($limit, $offset) {
        return $this->db->select("customers", "*",[
            "LIMIT" => [$offset, $limit],
            "ORDER" => ["id" => "DESC"]
        ]);
    }

    public function isUsedId($id){
        $isUsedInInvoice = $this->db->has("invoice", ["customers_id" => $id]);
        $isUsedInItemsCustomers = $this->db->has("items_customers", ["id_customers" => $id]);

        return $isUsedInInvoice || $isUsedInItemsCustomers;
    }

    public function getById($id) {
        return $this->db->get("customers", "*", ["id" => $id]);
    }

    public function getByEmail($email, $id){
        return $this->db->has("customers", [
            "email" => $email,
            "id[!]" => $id
        ]);
    }

    public function update($id, $ref_no, $name, $alamat, $nomer, $email) {
        return $this->db->update("customers", [
            "ref_no" => $ref_no,
            "name"   => $name,
            "alamat" => $alamat,
            "nomer" => $nomer,
            "email" => $email
        ], [
            "id" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("customers", ["id" => $id]);
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

        return $this->db->select("customers", "*", $where);
    }
}

$costumerModel = new Costumer($db);
