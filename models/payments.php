<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Payments {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getById($id) {
        return $this->db->get("payments", "*", [
            "id_payments" => $id
        ]);
    }

    public function getPayments($offset, $limit){
    return $this->db->select("payments", [
        "[>]invoice" => ["invoice_id" => "id_inv"],
        "[>]customers" => ["invoice.customers_id" => "id"]
    ], [
        "payments.id_payments(id)",
        "invoice.kode_inv",
        "invoice.tgl_inv",
        "customers.name",
        "payments.tanggal",
        "payments.nominal"
    ], [
        "GROUP" => "payments.id_payments",
        "LIMIT" => [$offset, $limit],
        "ORDER" => ["payments.id_payments" => "DESC"]
    ]);
}

public function getGrandTotalSisa($invoiceId) {
    // Total dari inv_items
    $itemData = $this->db->select('inv_items', [
        'total_sum' => \Medoo\Medoo::raw('SUM(total)')
    ], [
        'invoice_id' => $invoiceId
    ]);

    // Total dari payments
    $paymentData = $this->db->select('payments', [
        'nominal_sum' => \Medoo\Medoo::raw('SUM(nominal)')
    ], [
        'invoice_id' => $invoiceId
    ]);

    $totalItem = $itemData[0]['total_sum'] ?? 0;
    $totalBayar = $paymentData[0]['nominal_sum'] ?? 0;

    return (float) ($totalItem - $totalBayar);
}

public function getCount() {
    return $this->db->count("payments");
}

    public function insert($invoice_id, $tanggal, $nominal, $catatan = null) {
       return $this->db->insert("payments", [
          "invoice_id" => $invoice_id,         
          "tanggal" => $tanggal,
          "nominal" => $nominal,
          "catatan" => $catatan
       ]);
    }

    public function update($id, $invoice_id, $tanggal, $nominal, $catatan = null) {
        return $this->db->update("payments", [
            "invoice_id" => $invoice_id,
            "tanggal" => $tanggal,
            "nominal" => $nominal,
            "catatan" => $catatan
        ], [
            "id_payments" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("payments", [
            "id_payments" => $id
        ]);
    }

    public function search($keyword = '', $tglDari = '', $tglKe = '', $offset = null, $limit = null)
{
    $where = [];

    // Pencarian berdasarkan keyword
    if (!empty($keyword)) {
        $where['OR'] = [
            'invoice.kode_inv[~]' => $keyword,
            'payments.nominal[~]' => $keyword,
        ];
    }

    // Filter berdasarkan tanggal
    if (!empty($tglDari) && !empty($tglKe)) {
        $where['payments.tanggal[<>]'] = [$tglDari, $tglKe];
    } elseif (!empty($tglDari)) {
        $where['payments.tanggal[>=]'] = $tglDari;
    } elseif (!empty($tglKe)) {
        $where['payments.tanggal[<=]'] = $tglKe;
    }

    // Tambahkan limit dan offset
    $where['LIMIT'] = [$offset, $limit];

    // Eksekusi query
    return $this->db->select("payments", [
        "[>]invoice" => ["invoice_id" => "id_inv"],
        "[>]customers" => ["invoice.customers_id" => "id"]
    ], [
        "payments.id_payments(id)",
        "invoice.kode_inv",
        "invoice.tgl_inv",
        "customers.name(customer_name)",
        "payments.tanggal(tanggal_payments)",
        "payments.nominal"
    ], $where);
}


}

$payments = new Payments($db);