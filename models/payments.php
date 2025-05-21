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
        "customers.name(customer_name)",
        "payments.tanggal(tanggal_payments)",
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



    public function insert($invoice_id, $tanggal, $nominal) {
       return $this->db->insert("payments", [
          "invoice_id" => $invoice_id,         
          "tanggal" => $tanggal,
          "nominal" => $nominal
       ]);
    }

    public function update($id, $invoice_id, $tanggal, $nominal) {
        return $this->db->update("payments", [
            "invoice_id" => $invoice_id,
            "tanggal" => $tanggal,
            "nominal" => $nominal
        ], [
            "id_payments" => $id
        ]);
    }

    public function delete($id) {
        return $this->db->delete("payments", [
            "id_payments" => $id
        ]);
    }

    public function search($keyword = '', $tglDari = '', $tglKe = '')
{
    $where = [];

    // Pencarian berdasarkan keyword (misalnya invoice_id)
    if (!empty($keyword)) {
        $where['OR'] = [
            'invoice.kode_inv[~]' => $keyword,
            'payments.nominal[~]' => $keyword,
        ];
    }

    // Filter tanggal
    if (!empty($tglDari) && !empty($tglKe)) {
        $where['tanggal[<>]'] = [$tglDari, $tglKe]; // Antara dua tanggal
    } elseif (!empty($tglDari)) {
        $where['tanggal[>=]'] = $tglDari;
    } elseif (!empty($tglKe)) {
        $where['tanggal[<=]'] = $tglKe;
    }
    
    // Eksekusi query dan kembalikan hasil
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