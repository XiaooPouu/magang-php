<?php
session_start();
require_once __DIR__ . '/../models/suppliers.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$supplierModel = new Supplier($db);

// Tambah supplier
if (isset($_POST['add_supplier'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $existingSupplier = $supplierModel->getByRefNo($ref_no);
    // cek apakah ref no sudah ada
    if($existingSupplier){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $supplierModel->insert($ref_no, $name);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil ditambahkan!'
        ];
    }

    header("Location: ../pages/dataSuppliers.php");
    exit();
}

// Update supplier
if (isset($_POST['update_supplier'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $existingSupplier = $supplierModel->getByRefNo($ref_no);
    // cek apakah ref no sudah ada
    if($existingSupplier){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
    } else {
        // Simpan alert ke session
        $supplierModel->update($id, $ref_no, $name);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil diupdate!'
        ];
}

    header("Location: ../pages/dataSuppliers.php");
    exit();
}

// Detail supplier
if(isset($_GET['detail_supplier'])){
    $id = $_GET['detail_supplier'];
    $suppliers = $supplierModel->getById($id);
}

// Hapus supplier
if (isset($_GET['delete_supplier'])) {
    $id = $_GET['delete_supplier'];
    $supplierModel->delete($id);

        //  Notif Hapus
        $_SESSION['alert_delete'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil dihapus!'
        ];
    header("Location: ../pages/dataSuppliers.php");
    exit();
}
