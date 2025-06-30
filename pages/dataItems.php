<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include_once BASE_PATH . 'models/items.php';
require_once BASE_PATH . 'function/baseurl.php';


// Ambil data dari session kalau ada
$search = $_SESSION['items_data'] ?? [];

// pagination
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// hitung total data
$totalItems = $itemModel->getCount();

// hitung total halaman
$totalPages = ceil($totalItems / $perPage);

if (!empty($search)) {
  $items = $search; // hasil dari session
} else {
  $items = $itemModel->getWhitLimit($perPage, $offset);
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
  
  unset($_SESSION['alert_update']); // agar hanya tampil sekali
}
?>



<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Items</title>
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
          <?php  include BASE_PATH . 'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
             <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row mb-4">
              <div class="col-md-6"><h3 class="mb-4">Data Items</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataItems();?>">Data Items</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tabel Items</li>
                </ol>
              </div>
            </div>
      <div class="col-md-12">
         <!-- Alert Message -->
    <?= isset($input) ? $input : '' ?>
    <?= isset($edit) ? $edit : '' ?>
    <?= isset($hapus) ? $hapus : '' ?>
  </div>

  <!--begin::Input Group-->
                <div class="card card-primary card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title">Search Items</div>
                </div>
                  <!--end::Header-->
                  <form action="<?= $BaseUrl->getUrlControllerItems();?>" method="GET">
                    <input type="hidden" name="page" value="<?= $page ?>">
                  <!--begin::Body-->
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-12">
                          <label for="item_search" class="form-label">Kata Kunci:</label>
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Search Kata kunci"
                              aria-label="Ref_No"
                              name="search"
                              id="item_ref_no"
                              aria-describedby="basic-addon1"
                              value="<?= $_SESSION['search_keyword'] ?? null?>"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--end::Body-->

                  <!--begin::Footer-->
                  <div class="card-footer d-flex justify-content-end">
                          <a href="<?= $BaseUrl->getUrlControllerItems() . '?reset'?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
                          <button type="submit" class="btn btn-primary ms-2">
                            <i class="bi bi-search me-1"></i> Search</button>
                    </div>
                  </form>
                  <!--end::Footer-->
                  </div>
                  
                <!-- Table Items -->
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Table Items</h3></div>
                  <!-- button create -->
                    <div class="mt-3 mx-3">
                      <a href="<?= $BaseUrl->getUrlFormItems(); ?>" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Create New</a>
                    </div>
                    <!-- end button create -->
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Kode Items</th>
                          <th>Nama Barang</th>
                          <th class="text-end">Harga</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      <?php foreach ($items as $item): ?>
                      <tbody>
                        <tr class="align-middle">
                          <td><?= htmlspecialchars($item['ref_no'])?></td>
                          <td><?= htmlspecialchars($item['name'])?></td>
                          <td class="text-end">
                          <?= htmlspecialchars('Rp. ' . number_format($item['price'], 0,',','.'))?>
                          </td>
                          <td class="text-center">
                              <div class="btn-group">
                              <button
                                type="button"
                                class="btn btn-primary dropdown-toggle"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                              >
                                Actions
                              </button>
                              <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= $BaseUrl->getUrlFormItems($item['id']) ?>"><i class="bi bi-pencil me-1"></i> Edit</a></li>
                                <li><a class="dropdown-item" href="<?= $BaseUrl->getUrlControllerDelete($item['id']) ?>" onclick="return confirm('Are you sure?')"><i class="bi bi-trash me-1"></i>Delete</a></li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                        <?php endforeach;?> 
                      </tbody>
                    </table>
                  </div>
                  <!-- /.card-body -->
                  <div class="card-footer clearfix">
                    <ul class="pagination pagination-sm m-0 float-end">

                    <?php if ($page > 4): ?>
                      <li class="page-item"><a class="page-link" href="?page=1">1</a></li>
                      <li class="page-item disabled"><span class="page-link">...</span></li>
                    <?php endif; ?>

                    <?php
                      $startPage = max(1, $page - 2);
                      $endPage = min($totalPages, $page + 2);
                      for ($i = $startPage; $i <= $endPage; $i++):
                    ?>
                      <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages - 3): ?>
                      <li class="page-item disabled"><span class="page-link">...</span></li>
                      <li class="page-item"><a class="page-link" href="?page=<?= $totalPages ?>"><?= $totalPages ?></a></li>
                    <?php endif; ?>

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
<?php unset($_SESSION['form_data'])?>
