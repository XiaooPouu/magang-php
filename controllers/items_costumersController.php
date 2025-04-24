<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/items_costumer.php';

$db = (new Database())->getConnection();
$model = new ItemsCostumer($db);

$data = [];

if (isset($_POST['add_item_customer'])) {
    $item_id = $_POST['items_id'];
    $customer_id = $_POST['customers_id'];
    $price = $_POST['price'];

    $success = $model->insert($item_id, $customer_id, $price);

    $_SESSION['alert'] = [
        'type' => $success ? 'success' : 'danger',
        'message' => $success ? 'Items Customer berhasil ditambahkan!' : 'Gagal menambahkan data.'
    ];

    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit();
}

else if (isset($_GET['delete_ic'])){
    $id_ic = $_GET['delete_ic'];
    $success = $model->delete($id_ic);
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'Item berhasil dihapus!'
    ];
    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit();
}

// update item_customers
else if(isset($_POST['update_ic'])){
    $id_ic = $_POST['id_ic'];
    $item_id = $_POST['items_id'];
    $customer_id = $_POST['customers_id'];
    $price = $_POST['price'];

    $success = $model->update($id_ic, $item_id, $customer_id, $price);
    $_SESSION['alert_update'] = [
        'type' => 'success',
        'message' => 'Item berhasil diupdate!'
    ];
    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit();
}

// search
else if(isset($_GET['search'])){
    $keyword = trim($_GET['search']);
    $data = $model->search($keyword);

    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;

    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit();
}