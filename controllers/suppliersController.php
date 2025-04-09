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

    $supplierModel->insert($ref_no, $name);

    // Simpan alert ke session
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'Supplier berhasil ditambahkan!'
    ];

    header("Location: ../pages/read.php");
    exit();
}

// Update supplier
if (isset($_POST['update_supplier'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $supplierModel->update($id, $ref_no, $name);
    header("Location: ../pages/read.php");
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
    header("Location: ../pages/read.php");
    exit();
}
