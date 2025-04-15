<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/items_costumer.php';

$db = (new Database())->getConnection();
$model = new ItemsCostumer($db);

if (isset($_POST['add_item_customer'])) {
    $item_id = $_POST['items_id'];
    $customer_id = $_POST['customers_id'];
    $price = $_POST['price'];

    $success = $model->insert($item_id, $customer_id, $price);

    $_SESSION['alert'] = [
        'type' => $success ? 'success' : 'danger',
        'message' => $success ? 'Data berhasil ditambahkan!' : 'Gagal menambahkan data.'
    ];

    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit();
}
