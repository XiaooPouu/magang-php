<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/invoice.php';
include BASE_PATH . 'models/items.php';

$invoice_id = $_GET['id_inv'] ?? null;

$db = (new Database())->getConnection();

$model = new Invoice($db);
$invoiceData = $model->getById($invoice_id);
$itemsModel = new Item($db);
$items = $itemsModel->getAll();

// Validasi invoice
if (!$invoice_id || !$invoiceData) {
    die("Invoice tidak ditemukan.");
}

// Set session invoice_id
if (!isset($_SESSION['invoice_id']) && $invoice_id) {
    $_SESSION['invoice_id'] = $invoice_id;
}

$invoiceItemsModel = new InvoiceItems($db);
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
      <?php include BASE_PATH . 'includes/header.php'; ?>

      <!-- Sidebar -->
      <?php include BASE_PATH . 'includes/sidebar.php'; ?>

      <!-- Main Content -->
      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Input Items Invocie</h3>
              <h3>Invoice ke #<?= $invoice_id ?></h3>
            </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL?>pages/dataInvoiceItems.php?id_inv=<?= $invoice_id ?>">Data Items Invoices</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Input Form</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
            <div class="row g-4">
              <div class="col-12">

                <!-- Notifikasi -->
                <?php if (isset($_SESSION['alert'])): ?>
                  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                    <?= $_SESSION['alert']['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>
                  <?php unset($_SESSION['alert']); ?>
                <?php endif; ?>

                <!--begin::Form Validation-->
                <div class="card card-info card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title">Form Invoice Items</div></div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form action="<?= BASE_URL ?>controllers/invoice_itemsController.php" method="POST">
                  <input type="hidden" name="action" value="create">
                  <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">
                    <!--begin::Body-->
                    <div class="card-body">
                      <!--begin::Row-->
                      <div class="row g-3">
                       <!--begin::Col-->
                       <div class="col-md-4">
                       <label for="item" class="form-label">Items</label>
                    <select name="items_id" class="form-select" required>
                        <option value="">-- Pilih Item --</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                          <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                        <label for="qty" class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" value="1" required>
                          <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-4">
                          <label for="price" class="form-label">Harga (Opsional)</label>
                          <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                            <input
                              type="number"
                              class="form-control"
                              id="price"
                              aria-describedby="inputGroupPrepend"
                              name="price"
                              placeholder="Kosongkan untuk default harga item"
                            />
                            <div class="invalid-feedback">Please choose a username.</div>
                          </div>
                        </div>
                        <!--end::Col-->
                      </div>
                      <!--end::Row-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer d-flex align-items-center">
                    <a href="<?= BASE_URL ?>pages/dataInvoiceItems.php?id_inv=<?= $invoice_id ?>" class="btn btn-secondary">← Cancel</a>
                    <button type="submit" class="btn btn-info ms-auto">
                      <i class="bi bi-save me-1"></i> Submit
                    </button>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
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
