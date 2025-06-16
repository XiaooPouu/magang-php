<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/pic.php';

$id = $_POST['id'] ?? null;
$nama = $_POST['name'] ?? null;
$jabatan = $_POST['jabatan'] ?? null;
$email = $_POST['email'] ?? null;
$nomer = $_POST['nomer'] ?? null;

// menyimpan session form data
    $_SESSION['form_data'] = [
        'name' => $nama,
        'jabatan' => $jabatan,
        'email' => $email,
        'nomer' => $nomer
    ];

$emailExist = $picModel->getEmail($email);

if(isset($_POST['add_pic'])|| isset($_POST['update_pic'])){
    if($emailExist){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Email sudah terdaftar!'  
        ];
        header('Location:' . $BaseUrl->getUrlFormPIC());
        exit();
    } else if(!preg_match('/^\d+$/', $nomer)){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Nomor harus berupa angka!'  
        ];
        header('Location:' . $BaseUrl->getUrlFormPIC());
        exit();
    } else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Email tidak valid!'  
        ];
        header('Location:' . $BaseUrl->getUrlFormPIC());
        exit();
    }
}

if(isset($_POST['add_pic'])){
    $picModel->insert($nama, $jabatan, $email, $nomer);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'PIC berhasil disimpan!'  
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPIC());
    exit();
}

else if(isset($_POST['update_pic'])){
    if($id && $nama && $jabatan && $email && $nomer) {
    $picModel->update($id, $nama, $jabatan, $email, $nomer);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'PIC berhasil diupdate!'  
    ];
    unset($_SESSION['form_data']);
    header('Location:' . $BaseUrl->getUrlDataPIC());
    exit();
}
}

else if(isset($_GET['delete_pic'])){
    $id = $_GET['delete_pic'];
    $picModel->delete($id);
    $_SESSION['alert'] = [
        'type' => 'success',
        'message' => 'PIC berhasil dihapus!'  
    ];
    header('Location:' . $BaseUrl->getUrlDataPIC());
    exit();
}

else if (isset($_GET['toggle_status'])) {
    $id = $_GET['toggle_status'];
    $result = $picModel->toggleStatus($id);

    if ($result === 'already_in_use') {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'PIC lain masih dalam status USE. Nonaktifkan terlebih dahulu.'
        ];
    } elseif ($result && $result->rowCount() > 0) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Status berhasil diubah!'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'warning',
            'message' => 'Gagal mengubah status atau status tidak berubah.'
        ];
    }

    header('Location:' . $BaseUrl->getUrlDataPIC());
    exit();
}

else if (isset($_GET['search']) || isset($_GET['search_status'])){
    $keyword = $_GET['search'] ?? '';
    $status = $_GET['search_status'] ?? null;
    $data = $picModel->search($keyword, $status);
    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_status'] = $status;
    header('Location:' . $BaseUrl->getUrlDataPIC());
    exit();
}

if(isset($_GET['reset'])){
  unset($_SESSION['search_data']);
  unset($_SESSION['search_keyword']);
  unset($_SESSION['search_status']);

  header('Location:' . $BaseUrl->getUrlDataPIC());
  exit();
}
