<?php
require_once __DIR__ . '/../models/costumer.php';
require_once __DIR__ . '/../config/database.php';

$db = (new Database())->getConnection();
$costumerModel = new Costumer($db);

// Tambah costumer
if (isset($_POST['add_costumer'])) {
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $costumerModel->insert($ref_no, $name);
    header("Location: ../pages/read.php");
    exit();
}

// Update costumer
if (isset($_POST['update_costumer'])) {
    $id = $_POST['id'];
    $ref_no = $_POST['ref_no'];
    $name = $_POST['name'];

    $costumerModel->update($id, $ref_no, $name);
    header("Location: ../pages/read.php");
    exit();
}

//  Detail costumer
if (isset($_GET['detail_costumer'])) {
    $id = $_GET['detail_costumer'];
    $costumer = $costumerModel->getById($id);
}

// Hapus costumer
if (isset($_GET['delete_costumer'])) {
    $id = $_GET['delete_costumer'];
    $costumerModel->delete($id);
    header("Location: ../pages/read.php");
    exit();
}
