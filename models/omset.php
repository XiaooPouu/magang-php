<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Omset {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

   public function getOmsetHarian($search = null): array
{
    $result = $this->db->select("invoice", [
        "[><]inv_items" => ["id_inv" => "invoice_id"]
    ], [
        "invoice.tgl_inv(tanggal_raw)", // masih raw date untuk proses format nanti
        "total_omzet" => \Medoo\Medoo::raw("SUM(inv_items.qty * inv_items.price)")
    ], [
        "GROUP" => "invoice.tgl_inv",
        "ORDER" => ["invoice.tgl_inv" => "ASC"]
    ]);

    $data = [];

    foreach ($result as $row) {
        $tanggalFormatted = date('d-m-Y', strtotime($row['tanggal_raw']));

        // jika ada search, cek apakah $search ada di $tanggalFormatted
        if ($search !== null && stripos($tanggalFormatted, $search) === false) {
            continue; // skip data yang tidak cocok search
        }

        $data[] = [
            'tanggal' => $tanggalFormatted,
            'total_omzet' => $row['total_omzet']
        ];
    }

    return $data;
}


    public function getOmsetMingguan($search = null): array
{
    $result = $this->db->select("invoice", [
        "[><]inv_items" => ["id_inv" => "invoice_id"]
    ], [
        "minggu_ke" => \Medoo\Medoo::raw("WEEK(invoice.tgl_inv, 1)"),
        "total_omzet" => \Medoo\Medoo::raw("SUM(inv_items.qty * inv_items.price)")
    ], [
        "GROUP" => [ "minggu_ke"],
        "ORDER" => [ "minggu_ke" => "ASC"]
    ]);

    $data = [];

    foreach ($result as $row) {
        $mingguFormatted = $row['minggu_ke'];

        if ($search !== null && stripos($mingguFormatted, $search) === false) {
            continue;
        }

        $data[] = [
            'minggu_ke' => $mingguFormatted,
            'total_omzet' => $row['total_omzet']
        ];
    }

    return $data;
}



    public function getOmsetBulanan($search = null): array
{
    $result = $this->db->select("invoice", [
        "[><]inv_items" => ["id_inv" => "invoice_id"]
    ], [
        "bulan_raw" => \Medoo\Medoo::raw("DATE_FORMAT(invoice.tgl_inv, '%Y-%m')"),
        "total_omzet" => \Medoo\Medoo::raw("SUM(inv_items.qty * inv_items.price)")
    ], [
        "GROUP" => \Medoo\Medoo::raw("DATE_FORMAT(invoice.tgl_inv, '%Y-%m')"),
        "ORDER" => ["bulan_raw" => "ASC"]
    ]);

    $data = [];

    foreach ($result as $row) {
        $bulanFormatted = DateTime::createFromFormat('Y-m', $row['bulan_raw'])->format('F-Y');

        if ($search !== null && stripos($bulanFormatted, $search) === false) {
            continue;
        }

        $data[] = [
            'bulan' => $bulanFormatted,
            'total_omzet' => $row['total_omzet']
        ];
    }

    return $data;
}



}

$omsetModel = new Omset($db);