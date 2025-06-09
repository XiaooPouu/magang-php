<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';

class Company {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Ambil data company beserta nama PIC yang sedang di-use
    public function getCompany() {
        return $this->db->get("company",[
            "id",
            "nama_perusahaan",
            "alamat",
            "kota",
            "provinsi",
            "kode_pos",
            "negara",
            "email",
            "telepon"
        ]);
    }

    public function getStatusPIC() {
        return $this->db->get("pic", [
            "name",
            "nomer",
            "email",
        ], [
            "status" => "use"
        ]);
    }

    public function getCompanyById($id) {
        return $this->db->get("company", "*", ["id" => $id]);
    }

    public function getCompanyName(){
        return $this->db->get("company", ["nama_perusahaan"]);
    }

    public function update($id,$nama_perusahaan,$alamat,$kota,$provinsi,$kode_pos,$negara,$email,$telepon) {
        return $this->db->update("company", [
            "nama_perusahaan" => $nama_perusahaan,
            "alamat" => $alamat,
            "kota" => $kota,
            "provinsi" => $provinsi,
            "kode_pos" => $kode_pos,
            "negara" => $negara,
            "email" => $email,
            "telepon" => $telepon
        ],[
            "id" => $id
    ]);
    }
}

$companyModel = new Company($db);
