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

    public function searchAdvanced($keyword = '', $customerId = '', $tglDari = '', $tglKe = '') {
        $where = [];
    
        if (!empty($keyword)) {
            $where['kode_inv[~]'] = $keyword; // LIKE pencarian
        }
    
        if (!empty($customerId)) {
            $where['customers_id'] = $customerId;
        }
    
        if (!empty($tglDari) && !empty($tglKe)) {
            $where['tgl_inv[<>]'] = [$tglDari, $tglKe]; // BETWEEN
        } elseif (!empty($tglDari)) {
            $where['tgl_inv[>=]'] = $tglDari;
        } elseif (!empty($tglKe)) {
            $where['tgl_inv[<=]'] = $tglKe;
        }
    
        return $this->db->select('invoice', '*', [
            'AND' => $where,
            'ORDER' => ['id_inv' => 'DESC']
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

    public function getCount(){
        return $this->db->count("invoice");
    }

    public function getWithLimit($offset, $limit){
        return $this->db->select("invoice", [
            '[>]customers' => ['customers_id' => 'id']
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ],[
            "LIMIT" => [$offset, $limit],
            "ORDER" => ["id_inv" => "DESC"]
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
    public function search($keyword = '', $customerId = '', $tglDari = '', $tglKe = '') {
        // Inisialisasi array WHERE untuk filter pencarian
        $where = [];
    
        // Pencarian berdasarkan keyword
        if (!empty($keyword)) {
            $where['OR'] = [
                'invoice.kode_inv[~]' => $keyword, // Mencari di kode_inv
                'customers.name[~]' => $keyword,   // Mencari di nama customer
                'invoice.tgl_inv[~]' => $keyword  // Mencari di tanggal invoice
            ];
        }
    
        // Filter berdasarkan customer (jika ada)
        if (!empty($customerId)) {
            $where['invoice.customers_id'] = $customerId;
        }
    
        // Filter berdasarkan tanggal (jika ada)
        if (!empty($tglDari) && !empty($tglKe)) {
            $where['invoice.tgl_inv[<>]'] = [$tglDari, $tglKe]; // Rentang tanggal
        } elseif (!empty($tglDari)) {
            $where['invoice.tgl_inv[>=]'] = $tglDari; // Tanggal dari
        } elseif (!empty($tglKe)) {
            $where['invoice.tgl_inv[<=]'] = $tglKe; // Tanggal ke
        }
    
        // Query menggunakan Medoo untuk mencari data
        return $this->db->select('invoice', [
            '[>]customers' => ['customers_id' => 'id']
        ], [
            'invoice.id_inv',
            'invoice.kode_inv',
            'invoice.tgl_inv',
            'customers.name AS nama_customer'
        ], $where);
    }
    
}
?>
