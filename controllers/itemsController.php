<?php
session_start();
require_once __DIR__ . '/../models/items.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);



// Tambah item
if (isset($_POST['add_item'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $existingItem = $itemModel->getByRefNo($ref_no);
// cek apakah ref_no sudah ada
    if($existingItem){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $itemModel->insert($ref_no, $name, $price);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Item berhasil ditambahkan!'
        ];
    }
    
    header("Location: ../pages/dataItems.php");
    exit();
}

// Update item
if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $existingItem = $itemModel->getByRefNo($ref_no);
//    cek apakah ref_no sudah ada
    if($existingItem){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $itemModel->update($id, $ref_no, $name, $price);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Item berhasil diupdate!'
        ];
    }
    header("Location: ../pages/dataItems.php");
    exit();
}

// Detail item
if (isset($_GET['detail_item'])) {
    $id = $_GET['detail_item'];
    $item = $itemModel->getById($id);
}

// Hapus item
if (isset($_GET['delete_item'])) {
    $id = $_GET['delete_item'];
    $itemModel->delete($id);

    // Simpan alert_delete ke session
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'Item berhasil dihapus!'
    ];
    header("Location: ../pages/dataItems.php");
    exit();
}
