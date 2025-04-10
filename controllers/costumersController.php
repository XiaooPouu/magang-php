<?php
session_start();
require_once __DIR__ . '/../models/costumer.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$costumerModel = new Costumer($db);

// Tambah costumer
if (isset($_POST['add_costumer'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $existingCostumer = $costumerModel->getByRefNo($ref_no);

    // cek apakah ref_no sudah ada
    if($existingCostumer){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $costumerModel->insert($ref_no, $name);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil ditambahkan!'
        ];
    }

    header("Location: ../pages/dataCostumer.php");
    exit();
}

// Update costumer
if (isset($_POST['update_costumer'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $existingCostumer = $costumerModel->getByRefNo($ref_no);
    // cek apakah ref_no sudah ada
    if($existingCostumer){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $costumerModel->update($id, $ref_no, $name);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Costumer berhasil diupdate!'
        ];
    }

    header("Location: ../pages/dataCostumer.php");
    exit();
}

//  Detail costumer
if (isset($_GET['detail_costumer'])) {
    $id = $_GET['detail_costumer'];
    $costumer = $costumerModel->getById($id);
}

// Hapus costumer
if (isset($_GET['delete_costumer'])) {
    $id = $_GET['delete_costumer'];
    $costumerModel->delete($id);


    // simpan alert_delete ke session
    $_SESSION['alert_delete'] = [
      'type' => 'success',
      'message' => 'Costumer berhasil dihapus!'  
    ];
    header("Location: ../pages/dataCostumer.php");
    exit();
}
