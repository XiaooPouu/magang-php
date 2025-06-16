<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/payments.php';
require_once BASE_PATH . 'function/baseurl.php';

$id = $_POST['id_payments'] ?? null;
$invoice_id = $_POST['invoice_id'] ?? null;
$tanggal = $_POST['tanggal'] ?? null;
$nominal = $_POST['nominal'] ?? null;
$catatan = $_POST['catatan'] ?? null;

$_SESSION['form_data'] = [
        'invoice_id' => $invoice_id,
        'tanggal' => $tanggal,
        'nominal' => $nominal,
        'catatan' => $catatan,  
];

// Tambahkan validasi: nominal tidak boleh lebih dari sisa total invoice
if (isset($_POST['submit_payment']) || isset($_POST['update_payment'])) {
    $sisa = $payments->getGrandTotalSisa($invoice_id);
    if ($nominal > $sisa) {

        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Nominal pembayaran melebihi sisa tagihan (Rp. ' . number_format($sisa, 0, ',', '.') . ')'
        ];
        header('Location:' . $BaseUrl->getUrlFormPaymentsInvoice($invoice_id));
        exit();
    }
}

if (isset($_POST['submit_payment'])) {
    $sisa = $payments->getGrandTotalSisa($invoice_id);
    $payments->insert($invoice_id, $tanggal, $nominal, $catatan);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil, Anda Membayar Nominal Sebesar Rp. ' . number_format($nominal, 0, ',', '.') . '. Sisa Tagihan: Rp. ' . number_format($sisa - $nominal, 0, ',', '.')
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();

} else if (isset($_POST['update_payment'])) {
    if($id && $invoice_id && $tanggal && $nominal){
    $payments->update($id, $invoice_id, $tanggal, $nominal, $catatan);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil, Anda Membayar Nominal Sebesar Rp. ' . number_format($nominal, 0, ',', '.')
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();
    }
    

} else if(isset($_GET['search']) || isset($_GET['tgl_dari']) || isset($_GET['tgl_ke'])){
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;

    $totalPayments = $payments->getCount();
    $totalPages = ceil($totalPayments / $perPage);
    $keyword = $_GET['search'] ?? '';
    $tgl_dari = $_GET['tgl_dari'] ?? null;
    $tgl_ke = $_GET['tgl_ke'] ?? null;
    $dataPayments = $payments->search($keyword, $tgl_dari, $tgl_ke, $offset, $perPage);
    $_SESSION['search_data'] = $dataPayments;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_tgl_dari'] = $tgl_dari;
    $_SESSION['search_tgl_ke'] = $tgl_ke;
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();
} 
else if (isset($_GET['delete_payment'])) {
    $id = $_GET['delete_payment'];
    $payments->delete($id);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil dihapus!'
    ];
    unset($_SESSION['search_data']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();
}
else if(isset($_GET['reset'])){
    unset($_SESSION['search_data']);
    unset($_SESSION['search_keyword']);
    unset($_SESSION['search_tgl_dari']);
    unset($_SESSION['search_tgl_ke']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();
}
