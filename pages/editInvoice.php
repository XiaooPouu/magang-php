<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';
include BASE_PATH . 'models/costumer.php';


$db = (new Database())->getConnection();

$model = new Invoice($db);
$customersModel = new Costumer($db); // Ambil data customer

$customers = $customersModel->getAll();

$id_inv = $_GET['id_inv'];
$data = $model->getById($id_inv);

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>AdminLTE 4 | General Form Elements</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css" />
  </head>

  <body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <div class="app-wrapper">
      <nav class="app-header navbar navbar-expand bg-body">
        <div class="container-fluid"></div>
      </nav>

      <?php include BASE_PATH . 'includes/sidebar.php'; ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row g-4">
              <div class="col-md-12">

                <!-- Form Invoice -->
                <div class="card card-primary card-outline mb-4">
                  <div class="card-header">
                    <div class="card-title">Edit Invoice</div>
                  </div>
                  <form action="<?= BASE_URL ?>controllers/invoiceController.php" method="POST">
                    <div class="card-body row g-3">
                    <input type="hidden" name="id_inv" value="<?= $data['id_inv'] ?>">
                      <div class="col-md-6">
                        <label for="invoice_code" class="form-label">Kode Invoice</label>
                        <input type="text" name="kode_inv" class="form-control" id="invoice_code" required value="<?= $data['kode_inv'];?>">
                      </div>
                      <div class="col-md-6">
                        <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                        <input type="date" name="tgl_inv" class="form-control" id="invoice_date" required value="<?= $data['tgl_inv'];?>">
                      </div>
                      <div class="col-md-6">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customers_id" class="form-select" id="customer_id" required>
                          <option value="">-- Pilih Customer --</option>
                          <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id'] ?>"<?=($data['customers_id'] == $customer['id'] ? 'selected' : '')?>>
                              <?= htmlspecialchars($customer['name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" name="update_invoice" class="btn btn-primary">Save Invoice</button>
                      <a href="<?= BASE_URL ?>pages/dataInvoice.php" class="btn btn-secondary">Cancel</a>
                    </div>
                  </form>
                </div>
                <!-- End Form Invoice -->

              </div>
            </div>
          </div>
        </div>
      </main>

      <?php include BASE_PATH . 'includes/footer.php'; ?>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js" crossorigin="anonymous"></script>

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
  </body>
</html>
