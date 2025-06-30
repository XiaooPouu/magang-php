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
$logo = $_FILES['logo'] ?? null;
$ttd = $_FILES['tanda_tangan'] ?? null;

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

else if(isset($_POST['submit_logo'])){
    $uploadDirLogo = $BaseUrl->getUploadsLogo();
    $logoName = uniqid('logo_') . '.' . pathinfo($logo['name'], PATHINFO_EXTENSION);

    $Logo = move_uploaded_file($logo['tmp_name'], $uploadDirLogo . $logoName);
    
    if($id && $Logo){
        $companyModel->updateLogo($id,$logoName);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Logo dan Tanda Tangan berhasil diubah!'  
        ];
        header('Location:' . $BaseUrl->getInformasiPerusahaan());
        exit();

    }
} else if(isset($_POST['submit_ttd'])){
    $uploadDirTTD = $BaseUrl->getUploadsTTD();
    $tandaTanganName = uniqid('tanda_tangan_') . '.' . pathinfo($ttd['name'], PATHINFO_EXTENSION);
    $TTD = move_uploaded_file($ttd['tmp_name'], $uploadDirTTD . $tandaTanganName);

    if($id && $TTD){
        $companyModel->updateTTD($id, $tandaTanganName);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Logo dan Tanda Tangan berhasil diubah!'  
        ];
        header('Location:' . $BaseUrl->getInformasiPerusahaan());
        exit();
    }
}