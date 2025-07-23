<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'function/baseurl.php';

function checkLogin(){
    global $BaseUrl;
    if(!isset($_SESSION['user_id'])){
        header('Location:' . $BaseUrl->getUrlLogin());
        exit();
    }
}