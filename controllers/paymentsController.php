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

$_SESSION['form_data'] = [
    'invoice_id' => $invoice_id,
    'tanggal' => $tanggal,
    'nominal' => $nominal
];

// Tambahkan validasi: nominal tidak boleh lebih dari sisa total invoice
if (isset($_POST['submit_payment']) || isset($_POST['update_payment'])) {
    $sisa = $payments->getGrandTotalSisa($invoice_id);

    if ($nominal > $sisa) {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Nominal pembayaran melebihi sisa tagihan (Rp. ' . number_format($sisa, 0, ',', '.') . ')'
        ];
        header('Location:' . $BaseUrl->getUrlDataPayments());
        exit();
    }
}

if (isset($_POST['submit_payment'])) {
    $payments->insert($invoice_id, $tanggal, $nominal);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil, Anda Membayar Nominal Sebesar Rp. ' . number_format($nominal, 0, ',', '.')
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();

} else if (isset($_POST['update_payment'])) {
    if($id && $invoice_id && $tanggal && $nominal){
    $payments->update($id, $invoice_id, $tanggal, $nominal);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil, Anda Membayar Nominal Sebesar Rp. ' . number_format($nominal, 0, ',', '.')
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();
    }
    

} else if (isset($_GET['delete_payment'])) {
    $id = $_GET['delete_payment'];
    $payments->delete($id);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Payment berhasil dihapus!'
    ];
    header('Location:' . $BaseUrl->getUrlDataPayments());
    exit();


} else if (isset($_GET['search']) || isset($_GET['tgl_dari']) || isset($_GET['tgl_ke'])) {
    $keyword = $_GET['search'] ?? '';
    $tgl_dari = $_GET['tgl_dari'] ?? null;
    $tgl_ke = $_GET['tgl_ke'] ?? null;

    // Mengambil data berdasarkan parameter pencarian
    $data = $payments->search($keyword, $tgl_dari, $tgl_ke);

    // Simpan hasil pencarian ke session
    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_tgl_dari'] = $tgl_dari;
    $_SESSION['search_tgl_ke'] = $tgl_ke;

    header('Location: ' . $BaseUrl->getUrlDataPayments());
    exit();
}
