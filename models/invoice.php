<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php'; // Pastikan database.php sudah berisi pengaturan Medoo
class Invoice {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Membuat invoice baru
    public function createInvoice($kode_inv, $tgl_inv, $customers_id) {
        return $this->db->insert('invoice', [
            'kode_inv' => $kode_inv,
            'tgl_inv' => $tgl_inv,
            'customers_id' => $customers_id
        ]);
    }

    // Mendapatkan semua invoice beserta nama customer
    public function getByAll() {
        return $this->db->select('invoice', [
            '[>]customers' => ['customers_id' => 'id'],
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ], [
            'ORDER' => ['invoice.id_inv' => 'DESC']
        ]);
    }

    // Mendapatkan invoice berdasarkan kode_inv
    public function getBykode_inv($kode_inv) {
        return $this->db->get('invoice', [
            '[>]customers' => ['customers_id' => 'id']
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ], [
            'invoice.kode_inv' => $kode_inv
        ]);
    }

    // Mendapatkan invoice berdasarkan id_inv
    public function getById($id_inv) {
        return $this->db->get('invoice', [
            '[>]customers' => ['customers_id' => 'id']
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ], [
            'invoice.id_inv' => $id_inv
        ]);
    }

    // Mengupdate invoice
    public function update($id_inv, $kode_inv, $tgl_inv, $customers_id) {
        return $this->db->update('invoice', [
            'kode_inv' => $kode_inv,
            'tgl_inv' => $tgl_inv,
            'customers_id' => $customers_id
        ], [
            'id_inv' => $id_inv
        ]);
    }

    // Menghapus invoice
    public function delete($id_inv) {
        return $this->db->delete('invoice', [
            'id_inv' => $id_inv
        ]);
    }

    // Mencari invoice berdasarkan kata kunci
    public function search($keyword) {
        return $this->db->select('invoice', [
            '[>]customers' => ['customers_id' => 'id']
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ], [
            'OR' => [
                'invoice.kode_inv[~]' => $keyword,
                'customers.name[~]' => $keyword,
                'invoice.tgl_inv[~]' => $keyword
            ]
        ]);
    }
}
?>
