<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/invoice_items.php';
require_once BASE_PATH . 'function/baseurl.php';

$model = new InvoiceItems($db);

// Tangkap aksi dari POST/GET
$action = $_POST['action'] ?? $_GET['action'] ?? null;

    $id = $_POST['id'] ?? null;
    $invoice_id = $_POST['invoice_id'] ?? null;
    $items_id = $_POST['items_id'] ?? null;
    $qty = $_POST['qty'] ?? 1;
    $price = $_POST['price'] ?? 0;

switch ($action) {
    case 'create':

        if ($invoice_id && $items_id && $qty) {
            $model->insert($invoice_id, $items_id, $qty, $price);

            // ✅ Hapus session untuk hindari konflik redirect ke ID lama
            unset($_SESSION['invoice_id']);

            $_SESSION['alert'] = [
                'type' => 'success',
                'message' => 'Items berhasil disimpan!'
            ];

            // ✅ Redirect ke halaman dengan parameter yang sesuai
            header('Location:' . $BaseUrl->getUrlDetailInvoice($invoice_id));
            exit;
        } else {
            echo "Data tidak lengkap.";
        }
        break;

        case 'delete':
            $id = $_GET['id'] ?? null;
        
            if ($id) {
                // Ambil invoice_id sebelum delete untuk redirect
                $itemData = $model->getById($id);
                $invoice_id = $itemData['invoice_id'] ?? $_SESSION['invoice_id'] ?? null;
                
                unset($_SESSION['invoice_id']);
                if ($model->delete($id)) {
                    $_SESSION['alert_delete'] = [
                        'type' => 'success',
                        'message' => 'Items berhasil dihapus!'
                    ];
                    header('Location:' . $BaseUrl->getUrlDetailInvoice($invoice_id));
                    exit;
                } else {
                    echo "Gagal menghapus data.";
                }
            } else {
                echo "ID tidak ditemukan.";
            }
            break;

        case 'update':
            
            if($id && $invoice_id && $items_id && $qty){
                $model->update($id, $invoice_id, $items_id, $qty, $price);
                unset($_SESSION['invoice_id']);

                $_SESSION['alert'] = [
                    'type' => 'success',
                    'message' => 'Items berhasil diupdate!'
                ];
                header('Location: ' . $BaseUrl->getUrlDetailInvoice($invoice_id));
                exit;
            }
            break;
    default:
        echo "Aksi tidak dikenali.";
        break;
}

// Handle search di luar switch supaya bisa jalan tanpa "action"
if (isset($_GET['search'])) {
    $keyword = $_GET['search'];
    $invoice_id = $_GET['id_inv'] ?? null;

    if (!empty($keyword) && $invoice_id) {
        // Panggil fungsi search untuk mencari data dengan keyword dan invoice_id
        $invoiceItems = $model->search($keyword, $invoice_id);

        // Simpan data pencarian di session untuk digunakan di halaman dataInvoiceItems
        $_SESSION['search_data'] = $invoiceItems;
        $_SESSION['search_keyword'] = $keyword;

        // Redirect ke halaman dataInvoiceItems.php dengan parameter id_inv
        header('Location:' . $BaseUrl->getUrlDetailInvoice($invoice_id));
        exit;
    } else {
        // Jika keyword atau invoice_id tidak ditemukan
        echo "Keyword atau ID invoice tidak ditemukan.";
    }
}


