<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';
include BASE_PATH . 'models/costumer.php';


$db = (new Database())->getConnection();

$model = new Invoice($db);
$customersModel = new Costumer($db); // Ambil data customer

$customers = $customersModel->getAll();

$id_inv = $_GET['id_inv'];
$data = $model->getById($id_inv);

$customers_id = isset($formUpdate['customers_id']) ? $formUpdate['customers_id'] : '';

$formUpdate = isset($_SESSION['form_update']) ? $_SESSION['form_update'] : $data;
$alert = isset ($_SESSION['alert_update']);
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
      <?php include BASE_PATH . 'includes/header.php'; ?>

      <?php include BASE_PATH . 'includes/sidebar.php'; ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
             <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Form Invoice</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL?>pages/dataInvoice.php">Data Invoice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Form</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
            <div class="row g-4">
              <div class="col-md-12">
              <?php if ($alert): ?>
  <div class="alert alert-<?= $_SESSION['alert_update']['type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['alert_update']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION[$alert]); ?>
<?php endif; ?>
                <!-- Form Invoice -->
                <div class="card card-primary card-outline mb-4">
                  <div class="card-header">
                    <div class="card-title">Edit Invoice</div>
                  </div>
                  <form action="<?= BASE_URL ?>controllers/invoiceController.php" method="POST">
                    <div class="card-body row g-3">
                    <input type="hidden" name="id_inv" value="<?= $formUpdate['id_inv'] ?>">
                      <div class="col-md-4">
                        <label for="invoice_code" class="form-label">Kode Invoice</label>
                        <input type="text" name="kode_inv" class="form-control" id="invoice_code" required value="<?= $formUpdate['kode_inv'];?>">
                      </div>
                      <div class="col-md-4">
                        <label for="invoice_date" class="form-label">Tanggal Invoice</label>
                        <input type="date" name="tgl_inv" class="form-control" id="invoice_date" required value="<?= $formUpdate['tgl_inv'];?>">
                      </div>
                      <div class="col-md-4">
                        <label for="customer_id" class="form-label">Customer</label>
                        <select name="customers_id" class="form-select" id="customer_id" required>
  <?php foreach ($customers as $customer): ?>
    <option value="<?= $customer['id'] ?>" <?= ($customers_id == $customer['id'] ? 'selected' : '') ?>>
      <?= htmlspecialchars($customer['name']) ?>
    </option>
  <?php endforeach; ?>
</select>

                      </div>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                      <a href="<?= BASE_URL ?>pages/dataInvoice.php" class="btn btn-secondary">Cancel</a>
                      <button type="submit" name="update_invoice" class="btn btn-primary ms-auto">Submit</button>
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

<?php unset($_SESSION['form_update']);?>