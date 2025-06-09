<?php
session_start();
require_once __DIR__ . '/../config/env.php';
include BASE_PATH . "models/invoice.php";
include_once BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/costumer.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';

$invoiceModel = new Invoice($db);
$costumerModel = new Costumer($db);
$customers = $costumerModel->getAll();

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// hitung total data
$total_invoice = $invoiceModel->getCount();

// hitung total halaman
$totalPages = ceil($total_invoice / $perPage);

$keyword = $_GET['search'] ?? '';

// Jika tombol reset ditekan, bersihkan semua session pencarian
if (isset($_GET['reset'])) {
  unset($_SESSION['search_data']);
  unset($_SESSION['search_keyword']);
  unset($_SESSION['search_customer']);
  unset($_SESSION['search_tgl_dari']);
  unset($_SESSION['search_tgl_ke']);
  unset($_SESSION['search_status_lunas']); 
}

if (isset($_SESSION['search_data'])) {
  $data = $_SESSION['search_data'];
} else {
  $data = $invoiceModel->getWithLimit($offset, $perPage); // ambil data();
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
    <title>Data Invoices</title>
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
          <?php  include_once BASE_PATH .'includes/sidebar.php'  ?>
            <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row mb-4">
              <div class="col-sm-6"><h3 class="mb-4">Data Invoice</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataInvoice();?>">Data Invoice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Table Invoices</li>
                </ol>
              </div>
            </div>
            <div class="col-md-12">
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
      </div>

       <!--begin::Input Group-->
                <div class="card card-primary card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title">Search Invoice</div>
                </div>
                  <!--end::Header-->
                  <form action="<?= $BaseUrl->getUrlControllerInvoice();?>" method="GET">
                    <input type="hidden" name="page" value="<?= $page ?>">
                  <!--begin::Body-->
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <label for="invoice_search" class="form-label">Kata Kunci:</label>
                          <div class="input-group">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Search Kata kunci"
                              aria-label="Ref_No"
                              name="search"
                              id="invoice_search"
                              aria-describedby="basic-addon1"
                              value="<?= $_SESSION['search_keyword'] ?? '' ?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-2">
                          <label for="costumer" class="form-label">Pilih Customer:</label>
                          <div class="input-group">
                            <select name="customer" id="costumer" class="form-control">
                        <option value="">-- Pilih Customer --</option>
                        <?php
                        // Menampilkan list customer berdasarkan data yang ada di model atau tabel
                        foreach ($customers as $customer) {
                            $selected = ($_SESSION['search_customer'] ?? '') == $customer['id'] ? 'selected' : '';
                            echo '<option value="' . $customer['id'] . '" ' . $selected . '>' . htmlspecialchars($customer['name']) . '</option>';
                        }
                        ?>
                    </select>
                          </div>
                        </div>

                        <div class="col-md-2">
                          <label for="status" class="form-label">Pilih Status:</label>
                          <div class="input-group">
                             <?php $status = $_SESSION['search_status_lunas'] ?? ''; ?>
<select name="status_lunas" class="form-control" id="status">
    <option value="">-- Status --</option>
    <option value="lunas" <?= $status === 'lunas' ? 'selected' : '' ?>>Lunas</option>
    <option value="belum_lunas" <?= $status === 'belum_lunas' ? 'selected' : '' ?>>Belum Lunas</option>
</select>

                          </div>
                        </div>

                        <div class="col-md-2">
                          <label for="tgl_dari" class="form-label">Tanggal Dari:</label>
                          <div class="input-group">
                             <input type="date" name="tgl_dari" id="tgl_dari" class="form-control" value="<?= $_SESSION['search_tgl_dari'] ?? '' ?>" placeholder="Tanggal Dari">
                          </div>
                        </div>

                        <div class="col-md-2">
                          <label for="tgl_ke" class="form-label">Tanggal Ke:</label>
                          <div class="input-group">
                            <input type="date" name="tgl_ke" id="tgl_ke" class="form-control" value="<?= $_SESSION['search_tgl_ke'] ?? '' ?>" placeholder="Tanggal Ke">

                          </div>
                        </div>
                      </div>
                    </div>
                    <!--end::Body-->

                  <!--begin::Footer-->
                  <div class="card-footer d-flex justify-content-end">
                          <a href="<?= $BaseUrl->getUrlDataInvoiceReset();?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset</a>
                          <button type="submit" class="btn btn-primary ms-2">
                            <i class="bi bi-search me-1"></i> Search</button>
                    </div>
                  </form>
                  <!--end::Footer-->
                  </div>


  <!-- Table Items -->
  <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Table Invoice</h3>
                </div>

                <!-- button create -->
            <div class="mx-3 mt-3">
              <a href="<?= $BaseUrl->getUrlFormInvoice();?>" class="btn btn-primary btn-sm">
              <i class="bi bi-plus-circle me-1"></i> Create New</a>
            </div>
            <!-- end button create -->
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Kode Invoice</th>
                          <th>Tanggal Invoice</th>
                          <th>Kode Costumer</th>
                          <th>Costumers</th>
                          <th>Tanggal Jatuh Tempo</th>
                          <th class="text-end">Grand Total</th>
                          <th class="text-center">Status</th>
                          <th class="text-center">Action</th>
                        </tr>
                      </thead>
                      
                        <tbody>
                      <?php foreach ($data as $row): ?>
                        <?php
                          $dataSisa = $invoiceModel->getSisa($row['id_inv']);
                          $sisa = $dataSisa['sisa'];

                          if ($row['grand_total'] == 0) {
                              $statusLunas = 'Belum Ada Item';
                              $warna = 'bg-secondary';
                          } else {
                              $statusLunas = $sisa <= 0 ? 'Lunas' : 'Belum Lunas';
                              $warna = $sisa <= 0 ? 'bg-success' : 'bg-danger';
                          }
                        ?>
                        <tr class="align-middle">
                          <td><?= htmlspecialchars($row['kode_inv'])?></td>
                          <td><?= htmlspecialchars($row['tgl_inv'])?></td>
                          <td><?= htmlspecialchars($row['ref_no'])?></td>
                          <td>
                          <?= htmlspecialchars($row['name'])?>    
                          </td>
                          
                          <td><?= htmlspecialchars($row['tgl_tempo'])?></td>
                          <td class="text-end"><?= htmlspecialchars('Rp ' . number_format($row['grand_total'], 0, ',', '.'))?></td>
                          <td class="text-center">
                            <span class="badge <?= $warna ?>">
                            <i class="bi bi-cash-coin me-1"></i><?= $statusLunas ?>
                          </span>
                          </td>
                          <td class="text-center">
                          <a href="<?= $BaseUrl->getUrlFormInvoice($row['id_inv'])?>" class="btn btn-sm btn-warning me-1">
              <i class="bi bi-pencil-square me-1"></i>Edit</a>
              <a href="<?= $BaseUrl->getUrlDetailInvoice($row['id_inv'])?>" class="btn btn-sm btn-primary me-1">
              <i class="bi bi-file-earmark-text me-1"></i>Detail</a>
              <a href="<?= $BaseUrl->getUrlControllerDeleteInvoice($row['id_inv']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
              <i class="bi bi-trash me-1"></i>Delete</a>
                          </td>
                        </tr>
                        <?php endforeach;?>

                      </tbody>
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

<?php unset($_SESSION['form_data'])?>