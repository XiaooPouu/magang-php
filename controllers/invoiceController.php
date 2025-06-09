<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';
require_once BASE_PATH . 'function/baseurl.php';

$model = new Invoice($db);

$id_inv = $_POST['id_inv'] ?? null;
$kode_inv = $_POST['kode_inv'] ?? null;
$tgl_inv = $_POST['tgl_inv'] ?? null;
$tgl_tempo = $_POST['tgl_tempo'] ?? null;
$customer_id = $_POST['customers_id'] ?? null;
$note = $_POST['note'] ?? null;

// menyimpan session form data
    $_SESSION['form_data'] = [
        'kode_inv' => $kode_inv,
        'tgl_inv' => $tgl_inv,
        'customers_id' => $customer_id,
        'tgl_tempo' => $tgl_tempo,
        'note' => $note
    ];


if(isset($_POST['save_invoice']) || isset($_POST['update_invoice'])){
    if(strtotime($tgl_tempo) < strtotime($tgl_inv)){
            $_SESSION['alert'] = [
                'type' => 'danger',
                'message' => 'Tanggal Tempo harus lebih besar dari Tanggal Invoice!'
            ];
            header('Location:' . $BaseUrl->getUrlFormInvoice());
            exit();
        }
}


if(isset($_POST['save_invoice'])){

    $existingInvoice = $model->getBykode_inv($kode_inv);

    if($existingInvoice){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Kode Invoice sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlFormInvoice());
        exit();
    } else {
        $model->createInvoice($kode_inv, $tgl_inv, $customer_id, $tgl_tempo, $note);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Invoice berhasil disimpan!'
        ];

        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataInvoice());
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
      unset($_SESSION['search_data']);
    header('Location: ' . $BaseUrl->getUrlDataInvoice());
    exit();
}
// update invoice 
else if (isset($_POST['update_invoice'])){

    if($id_inv && $kode_inv && $tgl_inv && $customer_id && $tgl_tempo) {
        $model->update($id_inv, $kode_inv, $tgl_inv, $customer_id, $tgl_tempo, $note);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Invoice berhasil diupdate!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataInvoice());
        exit();
    }
}

// Pencarian invoice
else if (isset($_GET['search']) || isset($_GET['customer']) || isset($_GET['tgl_dari']) || isset($_GET['tgl_ke']) || isset($_GET['status_lunas'])) {
    $keyword = $_GET['search'] ?? '';
    $customer_id = $_GET['customer'] ?? null;
    $tgl_dari = $_GET['tgl_dari'] ?? null;
    $tgl_ke = $_GET['tgl_ke'] ?? null;
    $statusLunas = $_GET['status_lunas'] ?? null;

    // Mengambil data berdasarkan parameter pencarian
    $data = $model->search($keyword, $customer_id, $tgl_dari, $tgl_ke, $statusLunas);

    // Simpan hasil pencarian ke session
    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_customer'] = $customer_id;
    $_SESSION['search_tgl_dari'] = $tgl_dari;
    $_SESSION['search_tgl_ke'] = $tgl_ke;
    $_SESSION['search_status_lunas'] = $statusLunas;

    header('Location: ' . $BaseUrl->getUrlDataInvoice());
    exit();
}

