<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/items.php';
require_once BASE_PATH . 'function/baseurl.php';

// Tangkap data dari form
$ref_no = $_POST['ref_no'] ?? null;
$name   = $_POST['name'] ?? null;
$price  = $_POST['price'] ?? null;
$id     = $_POST['id'] ?? null;

$_SESSION['form_data'] = [
    'ref_no' => $ref_no,
    'name' => $name,
    'price' => $price
];

// Tambah item
if (isset($_POST['add_item'])) {
    // Validasi apakah item sudah ada
    $existingItem = $itemModel->getByRefNo($ref_no, $id);
    if ($existingItem) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlFormItems());
        exit();
    } else {
        $itemModel->insert($ref_no, $name, $price);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Item berhasil ditambahkan!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataItems());
        exit();
    }
}

// Update item
else if (isset($_POST['update_item'])) {
    // validasi ref_no
    $existingItem = $itemModel->getByRefNo($ref_no, $id);

    if($existingItem){
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Validasi dan update item
     if ($id && $ref_no && $name && $price) {
        $itemModel->update($id, $ref_no, $name, $price);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Item berhasil diupdate!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataItems());
        exit();
    } else {
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Data tidak lengkap untuk update item!'
        ];
        header('Location:' . $BaseUrl->getUrlFormItems());
        exit();
    }
    }
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
        unset($_SESSION['items_data']);
    }
    
    header('Location:' . $BaseUrl->getUrlDataItems());
    exit();
}

// Pencarian item
else if (isset($_GET['search'])) {
    // pagination
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;
    $keyword = $_GET['search'];
    $results = $itemModel->search($keyword, $offset, $perPage);
    $_SESSION['items_data'] = $results;
    $_SESSION['search_keyword'] = $keyword;
    header('Location:' . $BaseUrl->getUrlDataItems());
    exit();
}
else if (isset($_GET['reset'])) {
    unset($_SESSION['items_data']);
    unset($_SESSION['search_keyword']);
    header('Location:' . $BaseUrl->getUrlDataItems());
    exit();
}

// Default redirect kalau tidak ada aksi
else {
    header('Location:' . $BaseUrl->getUrlDataItems());
    exit();
}
