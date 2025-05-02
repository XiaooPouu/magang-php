<?php
session_start();
require_once __DIR__ . '/../config/env.php';
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
    if ($existingItem) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . BASE_URL . 'pages/createItems.php');
        exit();
    } else {
        $itemModel->insert($ref_no, $name, $price);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Item berhasil ditambahkan!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . BASE_URL . 'pages/dataItems.php');
        exit();
    }
}

// Update item
else if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $_SESSION['form_update'] = [
        'id' => $id,
        'ref_no' => $ref_no,
        'name' => $name,
        'price' => $price
    ];

    $existingItem = $itemModel->getByRefNo($ref_no);
    if ($existingItem && $existingItem['id'] != $id) {
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . BASE_URL . 'pages/editItems.php?id=' . $id);
        exit();
    } else {
        $itemModel->update($id, $ref_no, $name, $price);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Item berhasil diupdate!'
        ];
        unset($_SESSION['form_update']);
        header('Location:' . BASE_URL . 'pages/dataItems.php');
        exit();
    }
}

// Detail item (jika digunakan)
else if (isset($_GET['detail_item'])) {
    $id = $_GET['detail_item'];
    $item = $itemModel->getById($id);
    $_SESSION['detail_item'] = $item;
    header('Location:' . BASE_URL . 'pages/detailItems.php');
    exit();
}

// Hapus item
else if (isset($_GET['delete_item'])) {
    $id = $_GET['delete_item'];

    if($itemModel->isUseId($id)){
        $_SESSION['alert_delete'] = [
            'type' => 'danger',
            'message' => 'Item tidak dapat dihapus karena masih digunakan di invoice items, atau items customers.'
        ];
    }
    else{
        $itemModel->delete($id);
        $_SESSION['alert_delete'] = [
            'type' => 'success',
            'message' => 'Item berhasil dihapus!'
        ];
    }
    
    header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
}

// Pencarian item
else if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $results = $itemModel->search($keyword);
    $_SESSION['items_data'] = $results;
    header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
}

// Default redirect kalau tidak ada aksi
else {
    header('Location:' . BASE_URL . 'pages/dataItems.php');
    exit();
}
