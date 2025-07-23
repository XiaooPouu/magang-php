<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include_once BASE_PATH . 'models/users.php';
require_once BASE_PATH . 'function/baseurl.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = $usersModel->getByUsername($username);
    if(($username != null && $password != null) && $user){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        $_SESSION['alert'] = 
        [
           'type' => 'success',
           'message' => 'Login Berhasil, Selamat Datang ' . $user['username']
        ];
        header('Location:' . $BaseUrl->getIndex());
        exit();
    } else {
        $_SESSION['alert'] = 
        [
           'type' => 'danger',
           'message' => 'Login Gagal, Silahkan coba lagi!'
        ];
        header('Location:' . $BaseUrl->getUrlLogin());
        exit();
    }
}
