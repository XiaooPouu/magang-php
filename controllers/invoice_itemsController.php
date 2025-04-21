<?php
session_start();
require_once __DIR__ . '/../env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/invoice_items.php';

$db = (new Database())->getConnection();
$model = new InvoiceItems($db);

// Tangkap aksi dari POST/GET
$action = $_POST['action'] ?? $_GET['action'] ?? null;

switch ($action) {
    case 'create':
        $invoice_id = $_POST['invoice_id'] ?? null;
        $items_id   = $_POST['items_id'] ?? null;
        $qty        = $_POST['qty'] ?? 1;
        $price      = $_POST['price'] ?? 0;

        if ($invoice_id && $items_id && $qty) {
            $model->insert($invoice_id, $items_id, $qty, $price);

            // ✅ Hapus session untuk hindari konflik redirect ke ID lama
            unset($_SESSION['invoice_id']);

            // ✅ Redirect ke halaman dengan parameter yang sesuai
            header('Location:' . BASE_URL . 'pages/dataInvoiceItems.php?id_inv=' . $invoice_id);
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
                    header('Location:' . BASE_URL . 'pages/dataInvoiceItems.php?id_inv=' . $invoice_id);
                    exit;
                } else {
                    echo "Gagal menghapus data.";
                }
            } else {
                echo "ID tidak ditemukan.";
            }
            break;

        case 'update':
            $id = $_POST['id'] ?? null;
            $invoice_id = $_POST['invoice_id'] ?? null;
            $items_id = $_POST['items_id'] ?? null;
            $qty = $_POST['qty'] ?? 1;
            $price = $_POST['price'] ?? 0;
            
            if($id && $invoice_id && $items_id && $qty){
                $model->update($id, $invoice_id, $items_id, $qty, $price);
                unset($_SESSION['invoice_id']);
                header('Location: ' . BASE_URL . 'pages/dataInvoiceItems.php?id_inv=' . $invoice_id);
                exit;
            }
            break;
    default:
        echo "Aksi tidak dikenali.";
        break;
}
