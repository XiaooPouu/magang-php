<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Kwitansi {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getKwitansi($id) {
        return $this->db->get("payments", [
            "[>]invoice" => ["invoice_id" => "id_inv"],
            "[>]customers" => ["invoice.customers_id" => "id"]
        ],[
            "payments.id_payments(id)",
            "invoice.kode_inv",
            "invoice.tgl_inv",
            "customers.name",
            "payments.tanggal",
            "payments.nominal"
        ], [
            "id_payments" => $id
        ]);
    }
}

$kwitansiModel = new Kwitansi($db);