<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/suppliers.php';
require_once BASE_PATH . 'function/baseurl.php';

$id = $_POST['id'] ?? null;
$ref_no = $_POST['ref_no'] ?? null;
$name = $_POST['name'] ?? null;

// Simpan alert ke session
    $_SESSION['form_data'] = [
        'ref_no' => $ref_no,
        'name' => $name
    ];

// Tambah supplier
if (isset($_POST['add_supplier'])) {

    $existingSupplier = $supplierModel->getByRefNo($ref_no);
    // cek apakah ref no sudah ada
    if($existingSupplier){
        // jika sudah ada maka notif error muncul
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Ref No sudah ada, silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlFormSupplier());
        exit();
    } else {
        // Simpan alert ke session
        $supplierModel->insert($ref_no, $name);
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil ditambahkan!'
        ];

        // unset session
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataSupplier());
    exit();
    }
}

// Update supplier
else if (isset($_POST['update_supplier'])) {

    if($id && $ref_no && $name){
        $supplierModel->update($id, $ref_no, $name);
        $_SESSION['alert_update'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil diupdate!'
        ];
        unset($_SESSION['form_data']);
        header('Location:' . $BaseUrl->getUrlDataSupplier());
        exit();
    } else {
        $_SESSION['alert_update'] = [
            'type' => 'danger',
            'message' => 'Data tidak lengkap untuk update supplier!'
        ];
        header('Location:' . $BaseUrl->getUrlFormSupplier());
        exit();
    }
}

// Detail supplier
else if(isset($_GET['detail_supplier'])){
    $id = $_GET['detail_supplier'];
    $suppliers = $supplierModel->getById($id);
}

// Hapus supplier
else if (isset($_GET['delete_supplier'])) {
    $id = $_GET['delete_supplier'];
    $supplierModel->delete($id);

        //  Notif Hapus
        $_SESSION['alert_delete'] = [
            'type' => 'success',
            'message' => 'Supplier berhasil dihapus!'
        ];
    unset($_SESSION['suppliers_data']);
    header('Location:' . $BaseUrl->getUrlDataSupplier());
    exit();
}

else if (isset($_GET['search'])) {
    // pagination
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $perPage = 5;
    $offset = ($page - 1) * $perPage;
    $keyword = $_GET['search'];
    $results = $supplierModel->search($keyword,$offset, $perPage);
    $_SESSION['suppliers_data'] = $results;
    $_SESSION['search_keyword'] = $keyword;
    header('Location:' . $BaseUrl->getUrlDataSupplier());
    exit();
}
else if(isset($_GET['reset'])){
    unset($_SESSION['suppliers_data']);
    unset($_SESSION['search_keyword']);
    header('Location:' . $BaseUrl->getUrlDataSupplier());
    exit();

}

else {
    header('Location:' . $BaseUrl->getUrlDataSupplier());
    exit();
}