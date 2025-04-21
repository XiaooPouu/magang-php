<?php
session_start();
require_once __DIR__ . '/../env.php';;
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/items_costumer.php';
include BASE_PATH . 'models/items.php';
include BASE_PATH . 'models/costumer.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);
$costumerModel = new Costumer($db);

$items = $itemModel->getAll();
$customers = $costumerModel->getAll();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Tambah Data Item Customer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
    <!-- CSS: AdminLTE + Bootstrap + Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
    <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css" />
  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <!-- Navbar -->
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid"></div>
      </nav>

      <!-- Sidebar -->
      <?php include BASE_PATH . 'includes/sidebar.php'; ?>

      <!-- Main Content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row g-4">
              <div class="col-12">
                <h1>Tambah Data Item Customer</h1>

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['alert'])): ?>
                  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['alert']['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>

                <!-- Form -->
                <form action="<?= BASE_URL ?>controllers/items_costumersController.php" method="POST" class="row g-3">
                  <div class="col-md-4">
                    <label for="items_id" class="form-label">Item</label>
                    <select name="items_id" class="form-select" required>
                      <option value="" selected disabled>-- Pilih Item --</option>
                      <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label for="customers_id" class="form-label">Customer</label>
                    <select name="customers_id" class="form-select" required>
                      <option value="" selected disabled>-- Pilih Customer --</option>
                      <?php foreach ($customers as $cust): ?>
                        <option value="<?= $cust['id'] ?>"><?= $cust['name'] ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="col-md-4">
                    <label for="price" class="form-label">Harga</label>
                    <input type="number" name="price" class="form-control" required>
                  </div>

                  <div class="col-12">
                    <button type="submit" name="add_item_customer" class="btn btn-primary">
                      <i class="bi bi-save me-1"></i> Simpan
                    </button>
                    <a href="<?= BASE_URL ?>pages/dataItems_Costumer.php" class="btn btn-secondary ms-2">← Kembali</a>
                  </div>
                </form>
                <!-- End Form -->
              </div>
            </div>
          </div>
        </div>
      </main>

      <!-- Footer -->
      <?php include BASE_PATH . 'includes/footer.php'; ?>
    </div>

    <!-- JS: AdminLTE + Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: 'os-theme-light',
              autoHide: 'leave',
              clickScroll: true,
            },
          });
        }
      });
    </script>
  </body>
</html>
