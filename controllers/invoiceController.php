<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';

$db = (new Database())->getConnection();
$model = new Invoice($db);

if(isset($_POST['save_invoice'])){
    $kode_inv = $_POST['kode_inv'];
    $tgl_inv = $_POST['tgl_inv'];
    $customer_id = $_POST['customers_id'];

    // menyimpan session form data
    $_SESSION['form_data'] = [
        'kode_inv' => $kode_inv,
        'tgl_inv' => $tgl_inv,
        'customers_id' => $customer_id
    ];

    $existingInvoice = $model->getBykode_inv($kode_inv);

    if($existingInvoice){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Kode Invoice sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . BASE_URL . 'pages/createCustomerInvoice.php');
        exit();
    } else {
        $model->createInvoice($kode_inv, $tgl_inv, $customer_id);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Invoice berhasil disimpan!'
        ];

        unset($_SESSION['form_data']);
        header('Location:' . BASE_URL . 'pages/dataInvoice.php');
        exit();
    }
}
// hapus invoice
else if (isset($_GET['delete_invoice'])) {
    $id_inv = $_GET['delete_invoice'];
    $success = $model->delete($id_inv);

    // simpan alert_delete ke session
    $_SESSION['alert_delete'] = [
        'type' => 'success',
        'message' => 'Invoice berhasil dihapus!'  
      ];

    header('Location: ' . BASE_URL . 'pages/dataInvoice.php');
    exit();
}
// update invoice 
else if (isset($_POST['update_invoice'])){
    $id_inv = $_POST['id_inv'];
    $kode_inv = $_POST['kode_inv'];
    $tgl_inv = $_POST['tgl_inv'];
    $customer_id = $_POST['customers_id'];

    // simpan form_update ke session
    $_SESSION['form_update'] = [
        'id_inv' => $id_inv,
        'kode_inv' => $kode_inv,
        'tgl_inv' => $tgl_inv,
        'customers_id' => $customer_id
    ];

    $existingInvoice = $model->getBykode_inv($kode_inv);

    if($existingInvoice){
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Kode Invoice sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . BASE_URL . 'pages/editInvoice.php' . '?id_inv=' . $id_inv);
        exit();
    }else {
        // simpan alert_update ke session
        $model->update($id_inv, $kode_inv, $tgl_inv, $customer_id);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Invoice berhasil diupdate!'
        ];
        // unser form update
        unset($_SESSION['form_update']);
        header('Location: ' . BASE_URL . 'pages/dataInvoice.php');
    exit();
    }
}

// Pencarian invoice
else if (isset($_GET['search']) || isset($_GET['customer']) || isset($_GET['tgl_dari']) || isset($_GET['tgl_ke'])) {
    $keyword = $_GET['search'] ?? '';
    $customer_id = $_GET['customer'] ?? null;
    $tgl_dari = $_GET['tgl_dari'] ?? null;
    $tgl_ke = $_GET['tgl_ke'] ?? null;

    // Mengambil data berdasarkan parameter pencarian
    $data = $model->search($keyword, $customer_id, $tgl_dari, $tgl_ke);

    // Simpan hasil pencarian ke session
    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_customer'] = $customer_id;
    $_SESSION['search_tgl_dari'] = $tgl_dari;
    $_SESSION['search_tgl_ke'] = $tgl_ke;

    header('Location: ' . BASE_URL . 'pages/dataInvoice.php');
    exit();
}

