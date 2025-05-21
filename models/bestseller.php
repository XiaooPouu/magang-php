<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';


class Bestseller {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

     public function getBestSeller($limit = 10) {
    return $this->db->select("inv_items", [
        "[>]items" => ["items_id" => "id"]
    ], [
        "items.ref_no(kode)",
        "items.name(nama)",
        "total_qty" => \Medoo\Medoo::raw("SUM(qty)")
    ], [
        "GROUP" => "inv_items.items_id",
        "ORDER" => ["total_qty" => "DESC"],
        "LIMIT" => $limit
    ]);
}

public function getCount() {
    return $this->db->count("inv_items");
}

    public function search($keyword) {
    return $this->db->select("inv_items", [
        "[>]items" => ["items_id" => "id"]
    ], [
        "items.ref_no(kode)",
        "items.name(nama)",
        "total_qty" => \Medoo\Medoo::raw("SUM(inv_items.qty)")
    ], [
        "AND" => [
            "OR" => [
                "items.name[~]" => $keyword,
                "items.ref_no[~]" => $keyword
            ]
        ],
        "GROUP" => "inv_items.items_id",
        "ORDER" => ["total_qty" => "DESC"]
    ]);
}


}

$modelBestSeller = new Bestseller($db);