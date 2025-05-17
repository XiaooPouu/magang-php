<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/items_costumer.php';
require_once BASE_PATH . 'function/baseurl.php';

$model = new ItemsCostumer($db);


$id_ic = $_POST['id_ic'] ?? null;
$item_id = $_POST['items_id'] ?? null;
$customer_id = $_POST['customers_id'] ?? null;
$price = $_POST['price'] ?? null;

// Tambah data
if (isset($_POST['add_ic'])) {
    $success = $model->insert($item_id, $customer_id, $price);
    $_SESSION['alert'] = [
        'type' => $success ? 'success' : 'danger',
        'message' => $success ? 'Items Customer berhasil ditambahkan!' : 'Gagal menambahkan data.'
    ];
    header('Location: ' . $BaseUrl->getUrlDataItemsCostumer());
    exit();
}

// Hapus data
else if (isset($_GET['delete_ic'])) {
    $id_ic = $_GET['delete_ic'];
    $success = $model->delete($id_ic);
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'Item Customer berhasil dihapus!'
    ];
    header('Location: ' . $BaseUrl->getUrlDataItemsCostumer());
    exit();
}

// Update data
else if (isset($_POST['update_ic'])) {
    if ($id_ic && $item_id && $customer_id && $price) {
        $success = $model->update($id_ic, $item_id, $customer_id, $price);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Item Customer berhasil diupdate!'
        ];
    } else {
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Gagal update. Pastikan semua field terisi.'
        ];
    }
    header('Location: ' . $BaseUrl->getUrlDataItemsCostumer());
    exit();
}

// Cari data
else if (isset($_GET['search'])) {
    $keyword = trim($_GET['search']);
    $data = $model->search($keyword);

    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;

    header('Location: ' . $BaseUrl->getUrlDataItemsCostumer());
    exit();
}

// Ambil data untuk edit
else if (isset($_GET['edit_ic'])) {
    $id_ic = $_GET['edit_ic'];
    $editData = $model->getById($id_ic);

    if ($editData) {
        $_SESSION['edit_ic'] = $editData;
    }

    header('Location: ' . $BaseUrl->getUrlDataItemsCostumer());
    exit();
}
