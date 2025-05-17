<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Omset {
    private $db;

    public function __construct($db){
        $this->db = $db;
    }

    public function getOmsetHarian(): array
    {
        $sql = "
            SELECT 
                i.tgl_inv AS tanggal,
                SUM(ii.qty * ii.price) AS total_omzet
            FROM 
                invoice i
            JOIN 
                inv_items ii ON ii.invoice_id = i.id_inv
            GROUP BY 
                i.tgl_inv
            ORDER BY 
                i.tgl_inv ASC
        ";

        $stmt = $this->db->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];

        foreach ($result as $row) {
            $data[] = [
                'tanggal' => date('d-m-Y', strtotime($row['tanggal'])),
                'total_omzet' => $row['total_omzet']
            ];
        }
        return $data;
    }

    public function getOmsetMingguan(): array{
        $query = "
         SELECT 
            WEEK(i.tgl_inv, 1) AS minggu_ke,
            SUM(ii.qty * ii.price) AS total_omzet
        FROM inv_items ii
        JOIN invoice i ON ii.invoice_id = i.id_inv
        GROUP BY minggu_ke
        ORDER BY minggu_ke
        ";

        $stmt = $this->db->pdo->query($query);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];
        foreach ($result as $row){
            $data[] = [
                'minggu_ke' => $row['minggu_ke'],
                'total_omzet' => $row['total_omzet']
            ];
        }
        return $data;
    }

    public function getOmsetBulanan(): array{
        $sql = "
            SELECT 
            DATE_FORMAT(i.tgl_inv, '%Y-%m') AS bulan,
            SUM(ii.qty * ii.price) AS total_omzet
        FROM 
            invoice i
        JOIN 
            inv_items ii ON ii.invoice_id = i.id_inv
        GROUP BY 
            DATE_FORMAT(i.tgl_inv, '%Y-%m')
        ORDER BY 
            DATE_FORMAT(i.tgl_inv, '%Y-%m') ASC
        ";

        $stmt = $this->db->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $data = [];

        foreach ($result as $row) {
            $bulanTahun = DateTime::createFromFormat('Y-m', $row['bulan'])->format('F-Y');

            $data[] = [
                'bulan' => $bulanTahun,
                'total_omzet' => $row['total_omzet']
            ];
        }
        return $data;
    }
}

$omsetModel = new Omset($db);