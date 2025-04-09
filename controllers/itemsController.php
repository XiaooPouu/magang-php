<?php
require_once __DIR__ . '/../models/items.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);

// Tambah item
if (isset($_POST['add_item'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $itemModel->insert($ref_no, $name, $price);
    header("Location: ../pages/read.php");
    exit();
}

// Update item
if (isset($_POST['update_item'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];
    $price = $_POST['price'];

    $itemModel->update($id, $ref_no, $name, $price);
    header("Location: ../pages/read.php");
    exit();
}

// Detail item
if (isset($_GET['detail_item'])) {
    $id = $_GET['detail_item'];
    $item = $itemModel->getById($id);
}

// Hapus item
if (isset($_GET['delete_item'])) {
    $id = $_GET['delete_item'];
    $itemModel->delete($id);
    header("Location: ../pages/read.php");
    exit();
}
