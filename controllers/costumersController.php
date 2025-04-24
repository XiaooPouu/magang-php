<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/costumer.php';

$db = (new Database())->getConnection();
$costumerModel = new Costumer($db);

// Tambah costumer
if (isset($_POST['add_costumer'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    // Simpan alert ke session
    $_SESSION['form_data'] = [
        'ref_no' => $ref_no,
        'name' => $name
    ];

    $existingCostumer = $costumerModel->getByRefNo($ref_no);

    // cek apakah ref_no sudah ada
    if($existingCostumer){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . BASE_URL . 'pages/createCostumer.php');
        exit();
    } else {
        // Simpan alert ke session
        $costumerModel->insert($ref_no, $name);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil ditambahkan!'
        ];

        // unset session
        unset($_SESSION['form_data']);
        header('Location:' . BASE_URL . 'pages/dataCostumer.php');
    exit();
    }
}

// Update costumer
else if (isset($_POST['update_costumer'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    // Simpan alert ke session
    $_SESSION['form_update'] = [
        'id' => $id,
        'ref_no' => $ref_no,
        'name' => $name
    ];

    $existingCostumer = $costumerModel->getByRefNo($ref_no);
    // cek apakah ref_no sudah ada
    if($existingCostumer){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];

        header('Location:' . BASE_URL . 'pages/editCostumer.php' . '?id=' . $id);
        exit();
    } else {
        // Simpan alert ke session
        $costumerModel->update($id, $ref_no, $name);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil diupdate!'
        ];

        // unset session
        unset($_SESSION['form_update']);
        header('Location:' . BASE_URL . 'pages/dataCostumer.php');
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
    $costumerModel->delete($id);


    // simpan alert_delete ke session
    $_SESSION['alert_delete'] = [
      'type' => 'success',
      'message' => 'Costumer berhasil dihapus!'  
    ];
    header('Location:' . BASE_URL . 'pages/dataCostumer.php');
    exit();
}

else if(isset($_GET['search'])){
    $keyword = $_GET['search'];
    $results = $costumerModel->search($keyword);
    $_SESSION['costumers_data'] = $results;
    header('Location:' . BASE_URL . 'pages/dataCostumer.php');
    exit();
}

else {
    header('Location:' . BASE_URL . 'pages/dataCostumer.php');
    exit();
}