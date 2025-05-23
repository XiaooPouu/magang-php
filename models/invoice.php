<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php'; // Pastikan database.php sudah berisi pengaturan Medoo
class Invoice {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Membuat invoice baru
    public function createInvoice($kode_inv, $tgl_inv, $customers_id, $tgl_tempo, $note = null) {
        return $this->db->insert('invoice', [
            'kode_inv' => $kode_inv,
            'tgl_inv' => $tgl_inv,
            'customers_id' => $customers_id,
            'tgl_tempo' => $tgl_tempo,
            'note'=> $note
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

   public function getWithLimit($offset, $limit) {
    return $this->db->select("invoice", [
        '[>]customers' => ['customers_id' => 'id'],
        "[>]inv_items" => ["id_inv" => "invoice_id"]  // pastikan ini LEFT JOIN
    ], [
        'invoice.id_inv',
        'invoice.kode_inv',
        'invoice.tgl_inv',
        'invoice.tgl_tempo',
        'customers.name(name)',
        'customers.ref_no(ref_no)',
         "grand_total" => \Medoo\Medoo::raw("COALESCE(SUM(inv_items.total), 0)")
    ], [
        "GROUP" => "invoice.id_inv",
        "LIMIT" => [$offset, $limit],
        "ORDER" => ["invoice.id_inv" => "DESC"]
    ]);
}

public function getGrandTotal($id_inv) {
    return $this->db->get('inv_items', [
        'grand_total' => \Medoo\Medoo::raw('SUM(total)')
    ], [
        'invoice_id' => $id_inv
    ]);
}

    public function getSisa($id_inv) {
    // Ambil total dari inv_items dengan alias 'total_item'
    $item = $this->db->get('inv_items', [
        'total_semua' => \Medoo\Medoo::raw('SUM(total)')
    ], [
        'invoice_id' => $id_inv
    ]);

    // Ambil total dari payments dengan alias 'total_bayar'
    $bayar = $this->db->get('payments', [
        'total_bayar' => \Medoo\Medoo::raw('SUM(nominal)')
    ], [
        'invoice_id' => $id_inv
    ]);

    // Ambil nilainya dan ubah null jadi 0
    $totalItem = (float)($item['total_semua'] ?? 0);
    $totalBayar = (float)($bayar['total_bayar'] ?? 0);

    $sisa = round($totalItem - $totalBayar, 2);

    return ['sisa' => $sisa];
}




    // Mendapatkan invoice berdasarkan id_inv
    public function getById($id_inv) {
    return $this->db->get("invoice", [
        "[>]customers" => ["customers_id" => "id"]
    ], [
        "invoice.id_inv",
        "invoice.kode_inv",
        "invoice.tgl_inv",
        "invoice.note",
        "invoice.customers_id",
        "invoice.tgl_tempo",
        "customers.name(name)"
    ], [
        "invoice.id_inv" => $id_inv
    ]);
}

    // Mengupdate invoice
    public function update($id_inv, $kode_inv, $tgl_inv, $customers_id, $tgl_tempo, $note = null) {
        return $this->db->update('invoice', [
            'kode_inv' => $kode_inv,
            'tgl_inv' => $tgl_inv,
            'customers_id' => $customers_id,
            'tgl_tempo' => $tgl_tempo,
            'note' => $note
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
    public function search($keyword = '', $customerId = '', $tglDari = '', $tglKe = '', $statusLunas = '') {
    // Inisialisasi array WHERE untuk filter pencarian
    $where = [];

    // Pencarian berdasarkan keyword
    if (!empty($keyword)) {
        $where['OR'] = [
            'invoice.kode_inv[~]' => $keyword,
            'customers.name[~]' => $keyword,
            'invoice.tgl_inv[~]' => $keyword
        ];
    }

    // Filter berdasarkan customer
    if (!empty($customerId)) {
        $where['invoice.customers_id'] = $customerId;
    }

    // Filter berdasarkan tanggal
    if (!empty($tglDari) && !empty($tglKe)) {
        $where['invoice.tgl_inv[<>]'] = [$tglDari, $tglKe];
    } elseif (!empty($tglDari)) {
        $where['invoice.tgl_inv[>=]'] = $tglDari;
    } elseif (!empty($tglKe)) {
        $where['invoice.tgl_inv[<=]'] = $tglKe;
    }

    // Tambahkan GROUP BY supaya SUM(inv_items.total) per invoice, bukan total semua
     $where['GROUP'] = 'invoice.id_inv';

    // Ambil data dasar dari invoice + customer + total item
    $data = $this->db->select('invoice', [
        '[>]customers' => ['customers_id' => 'id'],
        '[<]inv_items' => ['id_inv' => 'invoice_id']
    ], [
        'invoice.id_inv',
        'invoice.kode_inv',
        'invoice.tgl_inv',
        'customers.name(name)',
        'customers.ref_no(ref_no)',
        'grand_total' => \Medoo\Medoo::raw("COALESCE(SUM(inv_items.total), 0)")
    ], $where);

    // Jika tidak butuh filter status lunas, langsung return
    if (empty($statusLunas)) {
        return $data;
    }

    // Filter status lunas secara manual
    $filtered = [];
    foreach ($data as $inv) {
        $sisa = $this->getSisa($inv['id_inv'])['sisa'] ?? 0;
        $isLunas = $sisa <= 0;

        if (
            ($statusLunas === 'lunas' && $isLunas) ||
            ($statusLunas === 'belum_lunas' && !$isLunas)
        ) {
            $filtered[] = $inv;
        }
    }

    return $filtered;
}


    public function getAllInvoicesWithCustomer() {
    return $this->db->select("invoice", [
        "[>]customers" => ["customers_id" => "id"]
    ], [
        "invoice.id_inv",
        "invoice.kode_inv",
        "customers.name"
    ]);
}

    
}

$invoiceModel = new Invoice($db);