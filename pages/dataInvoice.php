<?php
session_start();
require_once __DIR__ . "/../config/config.php";
include BASE_PATH . "models/invoice.php";
include BASE_PATH . 'models/invoice_items.php';
require_once BASE_PATH . 'config/database.php';

$db = (new Database())->getConnection();

$invoiceModel = new Invoice($db);
$data = $invoiceModel->getByAll();

$keyword = $_GET['search'] ?? '';

if (isset($_SESSION['search_data'])) {
  $data = $_SESSION['search_data'];
  unset($_SESSION['search_data']); // biar gak nyangkut terus
  unset($_SESSION['search_keyword']);
} else {
  $data = $invoiceModel->getByAll();
}

// hapus notifikasi
if(isset($_SESSION['alert_delete'])) {
  $type = $_SESSION['alert_delete']['type'];
  $message = $_SESSION['alert_delete']['message'];
  $hapus = "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
      {$message}
      <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
      </div>";
  
  unset($_SESSION['alert_delete']); // agar hanya tampil sekali
}

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
<!-- allert hapus -->
  <?= isset($hapus) ? $hapus : '' ?>
  <!-- end allert -->

    <!-- notifikasi tambah berhasil -->
  <?php if (isset($_SESSION['alert'])): ?>
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['alert']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
<!-- end notifikasi tambah -->

 <!-- notifikasi update berhasil -->
 <?php if (isset($_SESSION['alert_update'])): ?>
    <div class="alert alert-<?= $_SESSION['alert_update']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['alert_update']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert_update']); ?>
<?php endif; ?>
<!-- end notifikasi update -->

    <!--begin::App Wrapper-->
      <div class="app-wrapper">
        <!--begin::Header-->
        <?php include BASE_PATH . 'includes/header.php' ?>
        <!--end::Header-->
          <?php  include BASE_PATH .'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <div class="container-fluid">
          <div class="row g-4">
      <div class="col-md-12">
      <div class="card-header border-0">
        <h3 class="card-title">Invoice</h3>
      </div>
  </div>

  <form action="<?= BASE_URL ?>controllers/invoiceController.php" method="GET" class="d-flex mb-3">
  <input type="text" name="search" class="form-control me-2" placeholder="Search">
  <button class="btn btn-primary m-2" type="submit">Search</button>
  <a href="<?= BASE_URL ?>pages/dataInvoice.php" class="btn btn-secondary m-2">Reset</a>
</form>
 <!-- TABEL INVOICE -->
 <div class="col-lg-12">
    <div class="card mb-4">
      <div class="card-header border-0">
        <h3 class="card-title">Invoice Customers</h3>
      </div>
        <!-- button create -->
  <div class="mx-3 mb-2">
    <a href="<?= BASE_URL?>pages/createCustomerInvoice.php" class="btn btn-primary btn-sm">
    <i class="bi bi-plus-circle me-1"></i> Create New</a>
  </div>
  <!-- end button create -->

      <div class="card-body table-responsive p-0">
        <table class="table table-striped align-middle">
          <thead>
            <tr>
              <th>Kode Inv</th>
              <th>Tanggal Inv</th>
              <th>Customers</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($data as $row): ?>
            <tr>
              <td><?= htmlspecialchars($row['kode_inv']) ?></td>
              <td><?= htmlspecialchars($row['tgl_inv']) ?></td>
              <td>
              <?= htmlspecialchars($row['name']) ?>
              </td>
              <td>
              <a href="<?= BASE_URL?>pages/editInvoice.php?id_inv=<?=$row['id_inv']?>" class="btn btn-sm btn-warning me-1">
              <i class="bi bi-pencil-square me-1"></i>Edit</a>
              <a href="<?= BASE_URL?>pages/dataInvoiceItems.php?id_inv=<?=$row['id_inv']?>" class="btn btn-sm btn-primary me-1">
              <i class="bi bi-file-earmark-text me-1"></i>Detail</a>
              <a href="<?= BASE_URL ?>controllers/invoiceController.php?delete_invoice=<?= $row['id_inv']?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
              <i class="bi bi-trash me-1"></i>Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- end table invoice -->
</div>
                  <!-- begin::JavaScript-->
                  <!-- <script>
                    // Example starter JavaScript for disabling form submissions if there are invalid fields
                    (() => {
                      'use strict';

                      // Fetch all the forms we want to apply custom Bootstrap validation styles to
                      const forms = document.querySelectorAll('.needs-validation');

                      // Loop over them and prevent submission
                      Array.from(forms).forEach((form) => {
                        form.addEventListener(
                          'submit',
                          (event) => {
                            if (!form.checkValidity()) {
                              event.preventDefault();
                              event.stopPropagation();
                            }

                            form.classList.add('was-validated');
                          },
                          false,
                        );
                      });
                    })();
                  </script> -->
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