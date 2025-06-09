<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Tunggakan {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getTunggakanCustomer() {
    // Ambil semua customer yang punya invoice
    $customers = $this->db->select("invoice", [
        "[>]customers" => ["customers_id" => "id"]
    ], [
        'customers.id(customer_id)',
        'customers.name',
    ], [
        "GROUP" => "customers.id"
    ]);

    $data = [];

    foreach ($customers as $row) {
        $customerId = $row['customer_id'];

        // Ambil semua ID invoice milik customer ini
        $invoices = $this->db->select("invoice", "id_inv", [
            "customers_id" => $customerId
        ]);

        if (empty($invoices)) {
            continue;
        }

        // Hitung total dari inv_items
        $totalItem = (float)($this->db->get("inv_items", [
            "total_semua" => \Medoo\Medoo::raw("SUM(total)")
        ], [
            "invoice_id" => $invoices
        ])['total_semua'] ?? 0);

        // Hitung total dari payments
        $totalBayar = (float)($this->db->get("payments", [
            "total_bayar" => \Medoo\Medoo::raw("SUM(nominal)")
        ], [
            "invoice_id" => $invoices
        ])['total_bayar'] ?? 0);

        $sisa = round($totalItem - $totalBayar, 2);

        // Ambil tanggal jatuh tempo terakhir
        $tglTempo = $this->db->get("invoice", [
            "tgl_tempo" => \Medoo\Medoo::raw("MAX(tgl_tempo)")
        ], [
            "customers_id" => $customerId
        ])['tgl_tempo'] ?? null;


        $data[] = [
            'customer_id' => $customerId,
            'name' => $row['name'],
            'grand_total' => $totalItem,
            'total_bayar' => $totalBayar,
            'sisa' => $sisa,
            'tgl_tempo' => $tglTempo
        ];
    }

    return $data;
}

public function search($keyword = '', $tglDari = '', $tglKe = '') {
    // Inisialisasi array WHERE untuk filter pencarian
    $where = [
        'GROUP' => 'customers.id',
        'ORDER' => 'invoice.tgl_tempo' // Urutkan langsung dalam query
    ];

    // Pencarian berdasarkan keyword
    if (!empty($keyword)) {
        $where['OR'] = [
            'customers.name[~]' => $keyword,
        ];
    }

    // Filter berdasarkan tanggal
    if (!empty($tglDari) && !empty($tglKe)) {
        $where['invoice.tgl_tempo[<>]'] = [$tglDari, $tglKe];
    } elseif (!empty($tglDari)) {
        $where['invoice.tgl_tempo[>=]'] = $tglDari;
    } elseif (!empty($tglKe)) {
        $where['invoice.tgl_tempo[<=]'] = $tglKe;
    }

    // Ambil semua customer yang memenuhi kriteria pencarian
    $customers = $this->db->select("invoice", [
        "[>]customers" => ["customers_id" => "id"]
    ], [
        'invoice.customers_id',
        'customers.name',
        'invoice.tgl_tempo' // Tambahkan tgl_tempo untuk sorting
    ], $where);

    $data = [];

    foreach ($customers as $row) {
        $customerId = $row['customers_id'];

        // Ambil semua ID invoice milik customer ini dengan filter yang sama
        $invoiceWhere = [
            "customers_id" => $customerId
        ];

        $invoices = $this->db->select("invoice", "id_inv", $invoiceWhere);

        if (empty($invoices)) {
            continue;
        }

        // Hitung total dari inv_items
        $totalItem = (float)($this->db->get("inv_items", [
            "total_semua" => \Medoo\Medoo::raw("SUM(total)")
        ], [
            "invoice_id" => $invoices
        ])['total_semua'] ?? 0);

        // Hitung total dari payments
        $totalBayar = (float)($this->db->get("payments", [
            "total_bayar" => \Medoo\Medoo::raw("SUM(nominal)")
        ], [
            "invoice_id" => $invoices
        ])['total_bayar'] ?? 0);

        $sisa = round($totalItem - $totalBayar, 2);

        // Ambil tanggal jatuh tempo terakhir dengan filter yang sama
        $tglTempo = $this->db->get("invoice", [
            "tgl_tempo" => \Medoo\Medoo::raw("MAX(tgl_tempo)")
        ], $invoiceWhere)['tgl_tempo'] ?? null;

        // Hanya tambahkan ke data jika ada sisa tagihan
        if ($sisa > 0) {
            $data[] = [
                'customer_id' => $customerId,
                'name' => $row['name'],
                'grand_total' => $totalItem,
                'total_bayar' => $totalBayar,
                'sisa' => $sisa,
                'tgl_tempo' => $tglTempo
            ];
        }
    }

    return $data;
}

public function getDetailTunggakanCustomer($customerId) {
    $result = $this->db->select("invoice", [
        "[>]customers" => ["customers_id" => "id"],
    ], [
        'invoice.id_inv',
        'invoice.kode_inv',
        'customers.name',
        'invoice.tgl_tempo'
    ], [
        'invoice.customers_id' => $customerId
    ]);

    $data = [];

    foreach ($result as $row) {
        $id_inv = $row['id_inv'];
        $kode_inv = $row['kode_inv'];

        // Total dari inv_items untuk invoice ini
        $item = $this->db->get('inv_items', [
            'total_semua' => \Medoo\Medoo::raw('SUM(total)')
        ], [
            'invoice_id' => $id_inv
        ]);

        $grandTotal = (float)($item['total_semua'] ?? 0);
        $totalBayar = 0;

        // Ambil riwayat pembayaran
        $payments = $this->db->select('payments', [
            'tanggal',
            'nominal'
        ], [
            'invoice_id' => $id_inv,
            'ORDER' => ['tanggal' => 'ASC']
        ]);

        if (!empty($payments)) {
            foreach ($payments as $pay) {
                $totalBayar += (float)$pay['nominal'];
                $data[] = [
                    'tanggal_bayar' => $pay['tanggal'],
                    'kode_inv' => $kode_inv,
                    'grand_total' => $grandTotal,
                    'jumlah_bayar' => $pay['nominal'],
                    'sisa' => max(0, $grandTotal - $totalBayar),
                    'nama_customer' => $row['name'],
                    'tgl_tempo' => $row['tgl_tempo']
                ];
            }
        } else {
            // Belum ada pembayaran
            $data[] = [
                'tanggal_bayar' => 'Belum bayar',
                'kode_inv' => $kode_inv,
                'grand_total' => $grandTotal,
                'jumlah_bayar' => 0,
                'sisa' => $grandTotal,
                'nama_customer' => $row['name'],
                'tgl_tempo' => $row['tgl_tempo']
            ];
        }
    }

    return $data;
}

public function getById($customerId) {
    return $this->db->get("invoice", [
        "[>]customers" => ["customers_id" => "id"]
    ], [
        "invoice.customers_id",
        "invoice.tgl_tempo",
        "customers.name",
        "customers.ref_no",
        "customers.alamat",
        "customers.nomer",
        "customers.email"
    ], [
        "invoice.customers_id" => $customerId
    ]);
}



}

$tunggakan = new Tunggakan($db);