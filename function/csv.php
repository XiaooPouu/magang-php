<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/costumer.php';

$csv = $_FILES['csv_file'] ?? null;

if(isset($_GET['export'])){
    $query = $costumerModel->getAllNoID();
    $folder = $BaseUrl->getExportCSV();

    $fileName = 'data_customers_' . date('Ymd_His') . '_' . uniqid() . '.csv';
    $filePath = $folder . $fileName;

    // menyimpan file di server
    $file = fopen($filePath, 'w');

    fputcsv($file, ['kode_customers', 'nama', 'alamat', 'telepon', 'email'], ';');

    if($query){
        foreach ($query as $row){
            fputcsv($file, $row, ';');
        }
    }
    fclose($file);

    // kirim file ke browser untuk di download
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    readfile($filePath); //kirim isi file ke browser
    exit();
}
else if(isset($_POST['import']) ){
   $fileTmpName = $csv['tmp_name'];

   $file = fopen($fileTmpName, 'r');
   $gagal = 0;
   $count = 0;

   while(($data = fgetcsv($file, 1000, ";")) !== false){
        if($count > 0){
            // kondisi dimana jika data itu ada yang kosong maka akan di lewati
            if(count($data) !== 5){
                $gagal++;
                continue;
            }
            $kode_costumer = trim($data[0]);
            $nama = trim($data[1]);
            $alamat = trim($data[2]);
            $telepon = trim($data[3]);
            $email = trim($data[4]);

            
            if($kode_costumer && $nama && $alamat && $telepon && $email){
                $existingCostumer = $costumerModel->getByRefNo($kode_costumer);
                if($existingCostumer){
                $costumerModel->updateByKode($nama, $alamat, $telepon,$email , $kode_costumer);
                } else {
                $costumerModel->insertData($kode_costumer, $nama, $alamat, $telepon, $email);
                }
            } else {
                $gagal++;
            }
        }
    $count++;
   }
    fclose($file);
    $_SESSION['alert'] = [
       'type' => 'success',
       'message' => 'Data berhasil di import, Jumlah data yang gagal masuk: ' . $gagal
   ];
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}