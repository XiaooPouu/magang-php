<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/tunggakan.php';

if(isset($_GET['search']) || isset($_GET['tgl_dari']) || isset($_GET['tgl_ke'])){
    $keyword = $_GET['search'] ?? '';
    $tgl_dari = $_GET['tgl_dari'] ?? null;
    $tgl_ke = $_GET['tgl_ke'] ?? null;
    $data = $tunggakan->search($keyword, $tgl_dari, $tgl_ke);
    $_SESSION['search_data'] = $data;
    $_SESSION['search_keyword'] = $keyword;
    $_SESSION['search_tgl_dari'] = $tgl_dari;
    $_SESSION['search_tgl_ke'] = $tgl_ke;
    header('Location:' . $BaseUrl->getUrlDataTunggakan());
    exit();
}

else if (isset($_GET['reset'])) {
    unset($_SESSION['search_data']);
    unset($_SESSION['search_keyword']);
    unset($_SESSION['search_tgl_dari']);
    unset($_SESSION['search_tgl_ke']);
    header('Location:' . $BaseUrl->getUrlDataTunggakan());
    exit();
}