<?php
require_once __DIR__ . '/../config/database.php'; // harus return instance Medoo

class ItemsCostumer {
    private $db;

    public function __construct($db) {
        $this->db = $db; // Medoo instance
    }

    public function getAll() {
        return $this->db->select("items_customers (ic)", [
            "[>]items (i)" => ["id_items" => "id"],
            "[>]customers (c)" => ["id_customers" => "id"]
        ], [
            "ic.id_ic",
            "i.name(items_name)",
            "c.name(customers_name)",
            "ic.price"
        ]);
    }

    public function getById($id_ic) {
        return $this->db->get("items_customers", "*", [
            "id_ic" => $id_ic
        ]);
    }

    public function update($id_ic, $item_id, $customer_id, $price) {
        return $this->db->update("items_customers", [
            "id_items"     => $item_id,
            "id_customers" => $customer_id,
            "price"        => $price
        ], [
            "id_ic" => $id_ic
        ]);
    }

    public function insert($item_id, $customer_id, $price) {
        return $this->db->insert("items_customers", [
            "id_items"     => $item_id,
            "id_customers" => $customer_id,
            "price"        => $price
        ]);
    }

    public function delete($id_ic) {
        return $this->db->delete("items_customers", [
            "id_ic" => $id_ic
        ]);
    }

    public function search($keyword) {
        return $this->db->select("items_customers (ic)", [
            "[>]items (i)" => ["id_items" => "id"],
            "[>]customers (c)" => ["id_customers" => "id"]
        ], [
            "ic.id_ic",
            "i.name(items_name)",
            "c.name(customers_name)",
            "ic.price"
        ], [
            "OR" => [
                "i.name[~]" => $keyword,
                "c.name[~]" => $keyword
            ]
        ]);
    }
}
