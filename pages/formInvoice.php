<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice.php';
include BASE_PATH . 'models/costumer.php';
require_once BASE_PATH . 'function/baseurl.php';

$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : ['kode_inv' => '', 'tgl_inv' => '', 'customers_id' => '', 'tgl_tempo' => '', 'note' => ''];
$alert = isset($_SESSION['alert']);

$back = $_GET['back'] ?? null;
if(isset($back)){
  $id_inv = $_GET['id_inv'];
  $backUrl = $BaseUrl->getUrlDetailInvoice($id_inv);
} else {
  $backUrl = $BaseUrl->getUrlDataInvoice();
}

$customersModel = new Costumer($db); // Ambil data customer
$customers = $customersModel->getAll();

$isEdit = false; // Variabel untuk menandakan mode edit
$invoice = null;

if(isset($_GET['id_inv'])){
  $id_inv = $_GET['id_inv'];
  $invoiceModel = new Invoice($db);
  $invoice = $invoiceModel->getById($id_inv);
  if($invoice){
    $formData = [
      'kode_inv' => $invoice['kode_inv'],
      'tgl_inv' => $invoice['tgl_inv'],
      'customers_id' => $invoice['customers_id'],
      'tgl_tempo' => $invoice['tgl_tempo'],
      'note' => $invoice['note']
    ];
  }
  $isEdit = true;
}

?>
<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Invoice</title>
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
    <link rel="stylesheet" href="<?= $BaseUrl->getUrlCSS();?>" />
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
          <?php  include_once BASE_PATH . 'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-md-6"><h3 class="mb-0">Invoice Form</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataInvoice();?>">Data Invoice</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Form' : 'Create Form' ?></li>
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

      <!-- notifikasi -->
  <?php if ($alert): ?>
  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['alert']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
<!-- end notifikasi -->

      
                <!-- Form Invoice -->
              <div class="card card-primary card-outline mb-4">
                  <div class="card-header">
                    <div class="card-title"><?= $isEdit ? 'Edit Invoice' : 'Input Invoice'?></div>
                  </div>
                  <form action="<?= $BaseUrl->getUrlControllerInvoice()?>" method="POST">
                    <?php if ($isEdit): ?>
                      <input type="hidden" name="id_inv" value="<?= htmlspecialchars($invoice['id_inv']) ?>">
                      <?php if(isset($back)):?>
                        <input type="hidden" name="back" value="true">
                        <?php endif;?>
                      <?php endif;?>
                    <div class="card-body row g-3">
                      <div class="col-md-3">
                        <label for="invoice_code" class="form-label">Kode Invoice:</label>
                        <input type="text" name="kode_inv" class="form-control" id="invoice_code" required value="<?= htmlspecialchars($formData['kode_inv'])?>" placeholder="Contoh: INV001">
                      </div>
                      <div class="col-md-3">
                        <label for="invoice_date" class="form-label">Tanggal Invoice:</label>
                        <input type="date" name="tgl_inv" class="form-control" id="invoice_date" required value="<?= htmlspecialchars($formData['tgl_inv'])?>">
                      </div>
                      <div class="col-md-3">
                        <label for="customer_id" class="form-label">Customer:</label>
                        <select name="customers_id" class="form-select" id="customer_id" required>
                          <option value="" disabled <?= empty($formData['customers_id']) ? 'selected' : ''?>>-- Pilih Customer --</option>
                          <?php foreach ($customers as $customer): ?>
                            <option value="<?= $customer['id']?>" <?= $formData['customers_id'] == $customer['id'] ? 'selected' : ''?>>
                              <?= htmlspecialchars($customer['name']) ?>
                            </option>
                          <?php endforeach; ?>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label for="invoice_tempo" class="form-label">Tanggal Jatuh Tempo:</label>
                        <input type="date" name="tgl_tempo" class="form-control" id="invoice_tempo" required value="<?= htmlspecialchars($formData['tgl_tempo'])?>">
                      </div>

                      <div class="col-md-12">
                        <label for="note">Catatan/Note:</label>
                        <textarea name="note" class="form-control" id="note" value ="<?= htmlspecialchars($formData['note'])?>">
                        </textarea>
                      </div>
                    </div>
                    <div class="card-footer d-flex align-items-center">
                      <a href="<?= $backUrl; ?>" class="btn btn-secondary" style="padding: 8px 16px;">
                        <i class="bi bi-x-circle me-1"></i> Cancel</a>
                      <button type="submit" name="<?= $isEdit ? 'update_invoice' : 'save_invoice'?>" class="btn btn-primary ms-auto" style="padding: 8px 16px;"><i class="bi bi-check-circle-fill me-1"></i> Submit</button>
                    </div>
                  </form>
                </div>
                <!-- End Form Invoice -->
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

<?php unset($_SESSION['form_data']);?>