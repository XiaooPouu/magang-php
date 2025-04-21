<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';

$db = (new Database())->getConnection();
$model = new Invoice($db);

if(isset($_POST['save_invoice'])){
    $kode_inv = $_POST['kode_inv'] ?? '';
    $tgl_inv = $_POST['tgl_inv'] ?? '';
    $customer_id = $_POST['customers_id'] ?? '';

    $success = $model->createInvoice($kode_inv, $tgl_inv, $customer_id);
   

    $_SESSION['alert'] = [
        'type' => $success ? 'success' : 'danger',
        'message' => $success ? 'Data berhasil ditambahkan!' : 'Gagal menambahkan data.'
    ];
    unset($_SESSION['alert']);

    header('Location: ' . BASE_URL . 'pages/datainvoice.php');
    exit();
}

else if (isset($_GET['delete_invoice'])) {
    $id_inv = $_GET['delete_invoice'];
    $success = $model->delete($id_inv);

    header('Location: ' . BASE_URL . 'pages/dataInvoice.php');
    exit();
}
// update invoice 
else if (isset($_POST['update_invoice'])){
    $id_inv = $_POST['id_inv'];
    $kode_inv = $_POST['kode_inv'];
    $tgl_inv = $_POST['tgl_inv'];
    $customer_id = $_POST['customers_id'];

    $success = $model->update($id_inv, $kode_inv, $tgl_inv, $customer_id);

    header('Location: ' . BASE_URL . 'pages/dataInvoice.php');
    exit();
}