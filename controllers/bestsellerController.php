<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/bestseller.php';


if(isset($_GET['search'])){
    $keyword = $_GET['search'] ?? '';

    $data = $modelBestSeller->search($keyword);

    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    header('Location:' . $BaseUrl->getUrlDataBestSeller());
    exit;
}