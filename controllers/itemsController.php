<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/items.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);



// Tambah item
if (isset($_POST['add_item'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $_SESSION['form_data'] = [
      'ref_no' => $ref_no,
      'name' => $name,
      'price' => $price
    ];

    $existingItem = $itemModel->getByRefNo($ref_no);
// cek apakah ref_no sudah ada
    if($existingItem){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];

        // kembali ke halaman form
        header('Location:' . BASE_URL . 'pages/createItems.php');
        exit();
    } else {
        // Simpan alert ke session
        $itemModel->insert($ref_no, $name, $price);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Item berhasil ditambahkan!'
        ];

        // session sekali muncul
        unset($_SESSION['form_data']);
        header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
    }
}

// Update item
if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    // Simpan alert ke session
    $_SESSION['form_update'] = [
        'id' => $id,
        'ref_no' => $ref_no,
        'name' => $name,
        'price' => $price
    ];

    $existingItem = $itemModel->getByRefNo($ref_no);
//    cek apakah ref_no sudah ada
    if($existingItem && $existingItem['id'] != $id){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];

        // kembali ke halaman form
        header('Location:' . BASE_URL . 'pages/editItems.php' . '?id=' . $id);
        exit();
    } else {
        // Simpan alert ke session
        $itemModel->update($id, $ref_no, $name, $price);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Item berhasil diupdate!'
        ];

        // session sekali muncul
        unset($_SESSION['form_update']);
        header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
    }
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
    header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
}
