<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/suppliers.php';
require_once BASE_PATH . 'function/baseurl.php';

$supplierModel = new Supplier($db);

$search = $_SESSION['suppliers_data'] ?? [];

unset($_SESSION['suppliers_data']);

// pagination
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// hitung total data
$totalSuppliers = $supplierModel->getCount();

// hitung total halaman
$totalPages = ceil($totalSuppliers / $perPage);

if(!empty($search)) {
  $suppliers = $search;
} else {
  $suppliers = $supplierModel->getWithLimit($perPage, $offset);
}


if(isset($_SESSION['alert'])) {
    $type = $_SESSION['alert']['type'];
    $message = $_SESSION['alert']['message'];
    $input = "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
            {$message}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    
    unset($_SESSION['alert']); // agar hanya tampil sekali
}

if(isset($_SESSION['alert_delete'])) {
    $type = $_SESSION['alert_delete']['type'];
    $message = $_SESSION['alert_delete']['message'];
    $hapus = "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
            {$message}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    
    unset($_SESSION['alert_delete']); // agar hanya tampil sekali
}

if(isset($_SESSION['alert_update'])) {
    $type = $_SESSION['alert_update']['type'];
    $message = $_SESSION['alert_update']['message'];
    $edit = "<div class='alert alert-{$type} alert-dismissible fade show' role='alert'>
            {$message}
            <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
          </div>";
    
    unset($_SESSION['alert_update   ']); // agar hanya tampil sekali
}
?>



<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Suppliers</title>
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
        <?php include BASE_PATH . 'includes/header.php' ?>
        <!--end::Header-->
          <?php  include_once BASE_PATH . 'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
             <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row mb-4">
              <div class="col-sm-6"><h3 class="mb-4">Data Suppliers</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataSupplier();?>">Data Suppliers</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tabel Suppliers</li>
                </ol>
              </div>

              <form action="<?= $BaseUrl->getUrlControllerSupplier(); ?>" method="GET" class="d-flex mt-md-3">
                <input type="hidden" name="page" value="<?= $page ?>">
                <input type="text" name="search" class="form-control me-2 mb-2" placeholder="Search">
                <button class="btn btn-primary me-2 mb-2" type="submit">Search</button>
                <a href="<?= $BaseUrl->getUrlDataSupplier(); ?>" class="btn btn-secondary me-2 mb-2">Reset</a>
              </form>
            </div>

            <div class="col-md-12">
        <!-- Alert Message -->
    <?= isset($input) ? $input : '' ?>
    <?= isset($edit) ? $edit : '' ?>
    <?= isset($hapus) ? $hapus : '' ?>
  </div>

              <!-- tabel Supplier -->
              <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Table Suppliers</h3></div>
                  <!-- button create -->
                    <div class="mt-3 mx-3">
                      <a href="<?= $BaseUrl->getUrlFormSupplier();?>" class="btn btn-primary btn-sm"><i class="bi bi-plus-circle me-1"></i> Create New</a>
                    </div>
                    <!-- end button create -->
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Kode Suppliers</th>
                          <th>Nama Suppliers</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <?php foreach ($suppliers as $sp):?>
                      <tbody>
                        <tr class="align-middle">
                          <td><?= htmlspecialchars($sp['ref_no'])?></td>
                          <td><?= htmlspecialchars($sp['name'])?></td>
                          <td class="text-center"><a href="<?= $BaseUrl->getUrlFormSupplier($sp['id'])?>" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil-square me-1"></i>Edit</a>
                           <a href="<?= $BaseUrl->getUrlControllerDeleteSupplier($sp['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash me-1"></i>Delete</a>
                          </td>
                        </tr>
                      </tbody>
                      <?php endforeach;?>
                    </table>
                  </div>
                  <!-- /.card-body -->
                    <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">
                      <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">&laquo;</a></li>
                      <?php endif; ?>

                      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                          <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                      <?php endfor; ?>

                      <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">&raquo;</a></li>
                      <?php endif; ?>
                    </ul>
                  </div>
                </div>
                <!-- /.card -->
            <!--end::Row-->
          </div>
          <!--end::Container-->
      

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
      <?php include BASE_PATH .'includes/footer.php'?>
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
