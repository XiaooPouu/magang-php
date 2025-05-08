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
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>AdminLTE 4 | General Form Elements</title>
    <!--begin::Primary Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="title" content="AdminLTE 4 | General Form Elements" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard"
    />
    <!--end::Primary Meta Tags-->
    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
    />
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      integrity="sha256-tZHrRjVqNSRyWg2wbppGnT833E/Ys0DHWGwT04GiqQg="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      integrity="sha256-9kPW/n5nn53j4WMRYAxe9c1rCY96Oogo/MKSVdKzPmI="
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="<?= BASE_URL?>src/css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
  </head>
  <!--end::Head-->
  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
      <div class="app-wrapper">
        <!--begin::Header-->
        <?php  include BASE_PATH . 'includes/header.php'  ?>
        <!--end::Header-->
          <?php  include BASE_PATH . 'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-md-6"><h3 class="mb-0">Detail Invoice Form</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL?>pages/dataInvoiceItems.php?id_inv=<?= $invoice_id ?>">Detail Invoice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Input Form</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
          <!--begin::Container Input-->
          <div class="container-fluid">
          <div class="row g-4">
      <div class="col-md-12">

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
                  <div class="card-header"><div class="card-title">Input Invoice Items</div></div>
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
                    <a href="<?= BASE_URL ?>pages/dataInvoiceItems.php?id_inv=<?= $invoice_id ?>" class="btn btn-secondary" style="padding: 8px 16px;">
                      <i class="bi bi-x-circle me-1"></i> Cancel</a>
                    <button type="submit" class="btn btn-info ms-auto text-white" style="padding: 8px 16px;">
                      <i class="bi bi-check-circle-fill me-1"></i> Submit
                    </button>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
              </div>


                  <!--end::Input Group-->
                  <!--end::JavaScript -->
                </div>
                <!--end::Form Validation-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <?php include BASE_PATH . 'includes/footer.php'?>
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      integrity="sha256-dghWARbRe2eLlIJ56wNB+b760ywulqK3DzZYEpsg2fQ="
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
      crossorigin="anonymous"
    ></script>

    <script
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <!-- <script src="../../../dist/js/adminlte.js"></script> -->
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!--end::Script-->
  </body>
  <!--end::Body-->
</html>
