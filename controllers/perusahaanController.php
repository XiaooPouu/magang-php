<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/company.php';


$id = $_POST['id'] ?? null;
$nama_perusahaan = $_POST['nama_perusahaan'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$kota = $_POST['kota'] ?? null;
$provinsi = $_POST['provinsi'] ?? null;
$kode_pos = $_POST['kode_pos'] ?? null;
$negara = $_POST['negara'] ?? null;
$telepon = $_POST['telepon'] ?? null;
$email = $_POST['email'] ?? null;

if(isset($_POST['update_perusahaan'])){
    if($id && $nama_perusahaan && $alamat && $kota && $provinsi && $kode_pos && $negara && $telepon && $email){
        $companyModel->update($id,$nama_perusahaan,$alamat,$kota,$provinsi,$kode_pos,$negara,$email,$telepon);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Perusahaan berhasil diubah!'  
        ];
        header('Location:' . $BaseUrl->getInformasiPerusahaan());
        exit();
    }
}