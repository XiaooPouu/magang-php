<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/costumer.php';
require_once BASE_PATH . 'function/baseurl.php';

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

$emailSama = $costumerModel->getByEmail($email, $id);

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

    $existingCostumer = $costumerModel->getByRefNo($ref_no, $id);

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

    $existingCostumer = $costumerModel->getByRefNo($ref_no, $id);
    if($existingCostumer){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'  
        ];
    } else {
        if($id && $ref_no && $name){
        $costumerModel->update($id, $ref_no, $name, $alamat, $nomer, $email);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil diupdate!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataCostumer());
        exit();
    } else {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Data tidak lengkap untuk update costumer!'
        ];
        header('Location:' . $BaseUrl->getUrlFormCostumer());
        exit();
    }
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
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Customer tidak dapat dihapus karena masih digunakan di invoice, atau items customers.'
        ];
        header('Location:' . $BaseUrl->getUrlDataCostumer());
        exit();
     } else {
    $costumerModel->delete($id);

    // simpan alert_delete ke session
    $_SESSION['alert'] = [
      'type' => 'success',
      'message' => 'Costumer berhasil dihapus!'  
    ];
    unset($_SESSION['costumers_data']);
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}
}

else if(isset($_GET['search'])){
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;
    $keyword = $_GET['search'];
    $results = $costumerModel->search($keyword, $offset, $perPage);
    $_SESSION['costumers_data'] = $results;
    $_SESSION['search_keyword'] = $keyword;
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}
else if(isset($_GET['reset'])){
    unset($_SESSION['costumers_data']);
    unset($_SESSION['search_keyword']);
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();

}

else {
    header('Location:' . $BaseUrl->getUrlDataCostumer());
    exit();
}