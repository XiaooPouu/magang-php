<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';


class Bestseller {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

     public function getBestSeller($limit = 10): array {
     $query = "
        SELECT 
            items.ref_no AS kode, 
            items.name AS nama, 
            SUM(inv_items.qty) AS total_qty

        FROM inv_items

        JOIN items ON inv_items.items_id = items.id

        GROUP BY inv_items.items_id
        
        ORDER BY total_qty DESC
        LIMIT $limit
    ";

     $stmt = $this->db->pdo->query($query);

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $data = [];
    foreach ($result as $row){
        $data[] = [
            'kode' => $row['kode'],
            'nama' => $row['nama'],
            'total_qty' => $row['total_qty']
        ];
    }
    return $data;
}
}