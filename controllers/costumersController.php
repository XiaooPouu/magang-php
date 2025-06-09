<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/costumer.php';
require_once BASE_PATH . 'function/baseurl.php';

$costumerModel = new Costumer($db);

// Tangkap data dari form
$id = $_POST ['id'] ?? null;
$ref_no = $_POST['ref_no'] ?? null;
$name = $_POST['name'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$nomer = $_POST['nomer'] ?? null;
$email = $_POST['email'] ?? null;

$_SESSION['form_data'] = [
    'ref_no' => $ref_no,
    'name' => $name,
    'alamat' => $alamat,
    'nomer' => $nomer,
    'email' => $email
];

$emailSama = $costumerModel->getEmailById($id);

if(isset($_POST['add_costumer']) || isset($_POST['update_costumer'])){
    if($emailSama){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Email sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlFormCostumer());
        exit();
    }
}

// Tambah costumer
if (isset($_POST['add_costumer'])) {

    $existingCostumer = $costumerModel->getByRefNo($ref_no);

    // cek apakah ref_no sudah ada
    if($existingCostumer){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlFormCostumer());
        exit();
    } else {
        // Simpan alert ke session
        $costumerModel->insert($ref_no, $name, $alamat, $nomer, $email);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil ditambahkan!'
        ];

        // unset session
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
    }
}

// Update costumer
else if (isset($_POST['update_costumer'])) {
    if($id && $ref_no && $name){
        $costumerModel->update($id, $ref_no, $name, $alamat, $nomer, $email);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil diupdate!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataCostumer());
        exit();
    } else {
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Data tidak lengkap untuk update costumer!'
        ];
        header('Location:' . $BaseUrl->getUrlFormCostumer());
        exit();

    }
}

//  Detail costumer
else if (isset($_GET['detail_costumer'])) {
    $id = $_GET['detail_costumer'];
    $costumer = $costumerModel->getById($id);
}

// Hapus costumer
else if (isset($_GET['delete_costumer'])) {
    $id = $_GET['delete_costumer'];

     if($costumerModel->isUsedId($id)){
        $_SESSION['alert_delete'] = [
            'type' => 'danger',
            'message' => 'Customer tidak dapat dihapus karena masih digunakan di invoice, atau items customers.'
        ];
     } else {
    $costumerModel->delete($id);

    // simpan alert_delete ke session
    $_SESSION['alert_delete'] = [
      'type' => 'success',
      'message' => 'Costumer berhasil dihapus!'  
    ];
}

    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}

else if(isset($_GET['search'])){
    $keyword = $_GET['search'];
    $results = $costumerModel->search($keyword);
    $_SESSION['costumers_data'] = $results;
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}

else {
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}