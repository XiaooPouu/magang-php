<?php
session_start();
require_once __DIR__ . '/../config/database.php';
include '../models/items.php';
include '../models/costumer.php';
include '../models/suppliers.php';

if (isset($_SESSION['alert'])) {
  $type = $_SESSION['alert']['type']; // success, warning, dll
  $message = $_SESSION['alert']['message'];

  echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
          {$message}
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
  
  unset($_SESSION['alert']); // agar hanya tampil sekali
}

if (isset($_SESSION['alert_delete'])) {
  $type = $_SESSION['alert_delete']['type']; // success, warning, dll
  $message = $_SESSION['alert_delete']['message'];

  echo "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
          {$message}
          <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>";
  
  unset($_SESSION['alert_delete']); // agar hanya tampil sekali
}


// Buat koneksi dari class Database
$database = new Database();
$db = $database->getConnection();

$itemModel = new Item($db);
$customerModel = new Costumer($db);
$supplierModel = new Supplier($db);

$items = $itemModel->getAll();
$customers = $customerModel->getAll();
$suppliers = $supplierModel->getAll();
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>AdminLTE | Read Produk</title>

  <!-- Font & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <!-- AdminLTE & Bootstrap -->
  <link rel="stylesheet" href="../src/css/adminlte.css" />
  <!-- Scrollbars -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">

    <!-- Header -->
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid">
        <!-- Isi header opsional -->
      </div>
    </nav>

    <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <div class="sidebar-brand">
    <a href="../index.html" class="brand-link">
      <img src="../../../dist/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow"/>
      <span class="brand-text fw-light">AdminLTE 4</span>
    </a>
  </div>

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu">
      <li class="nav-item">
                <a href="../index.php" class="nav-link">
                  <i class="nav-icon bi bi-speedometer"></i>
                  <p>Dashboard</p>
                </a>
              </li>
        <!-- Tables Menu -->
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>
              Tables
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview ps-3">
            <li class="nav-item">
              <a href="input.php" class="nav-link">
                <i class="bi bi-plus-circle nav-icon me-2"></i>
                <p>Input</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="read.php" class="nav-link">
                <i class="bi bi-eye nav-icon me-2"></i>
                <p>Read</p>
              </a>
            </li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</aside>


    <!-- Main -->
    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Read</h3></div>
          </div>
        </div>
      </div>

      <div class="app-content">
        <div class="container-fluid">
        <div class="row">
  <!-- TABEL ITEMS -->
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header border-0">
        <h3 class="card-title">Items</h3>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>REF_NO</th>
              <th>NAME</th>
              <th>PRICE</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($items as $item): ?>
            <tr>
              <td><?= htmlspecialchars($item['ref_no'])?></td>
              <td><?= htmlspecialchars($item['name'])?></td>
              <td><?= htmlspecialchars($item['price'])?></td>
              <td>
                <a href="editItems.php?id=<?= $item['id']?>" class="btn btn-sm btn-warning me-1">Edit</a>
                <a href="../controllers/itemsController.php?delete_item=<?= $item['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TABEL CUSTOMERS -->
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header border-0">
        <h3 class="card-title">Customers</h3>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>REF_NO</th>
              <th>NAME</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customers as $cs): ?>
            <tr>
              <td><?= htmlspecialchars($cs['ref_no'])?></td>
              <td><?= htmlspecialchars($cs['name'])?></td>
              <td>
                <a href="editCostumer.php?id=<?=$cs['id']?>" class="btn btn-sm btn-warning me-1">Edit</a>
                <a href="../controllers/costumersController.php?delete_costumer=<?= $cs['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- TABEL SUPPLIERS -->
  <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header border-0">
        <h3 class="card-title">Suppliers</h3>
      </div>
      <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>REF_NO</th>
              <th>NAME</th>
              <th>ACTION</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($suppliers as $sp): ?>
            <tr>
              <td><?= htmlspecialchars($sp['ref_no'])?></td>
              <td><?= htmlspecialchars($sp['name'])?></td>
              <td>
                <a href="editSupplier.php?id=<?=$sp['id']?>" class="btn btn-sm btn-warning me-1">Edit</a>
                <a href="../controllers/suppliersController.php?delete_supplier=<?= $sp['id']?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
    </main>

    <!-- Footer -->
    <footer class="app-footer">
      <div class="float-end d-none d-sm-inline">Anything you want</div>
      <strong>&copy; 2014-2024 <a href="https://adminlte.io" class="text-decoration-none">AdminLTE.io</a>.</strong> All rights reserved.
    </footer>

  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const sidebarWrapper = document.querySelector('.sidebar-wrapper');
      if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
        OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
          scrollbars: {
            theme: 'os-theme-light',
            autoHide: 'leave',
            clickScroll: true
          },
        });
      }
    });
  </script>
</body>
</html>
