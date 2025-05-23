<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . "models/invoice.php";
include BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/costumer.php';
include BASE_PATH . 'models/items.php';
require_once BASE_PATH . 'function/baseurl.php';


$id_inv = $_GET['id_inv'] ?? $_SESSION['invoice_id'] ?? null;
$invoiceItemsModel = new InvoiceItems($db);

$invoiceModel = new Invoice($db);
$invoice = $invoiceModel->getById($id_inv);

if (!$invoice) {
    die("Data invoice tidak ditemukan.");
}

$keyword = $_GET['search'] ?? '';
$search_data = $_SESSION['search_data'] ?? [];

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 30;
$offset = ($page - 1) * $perPage;

// Jika ada pencarian, jalankan pencarian
if (!empty($keyword)) {
  $invoiceItems = $invoiceItemsModel->search($keyword, $id_inv);
  $_SESSION['search_data'] = $invoiceItems; // Simpan data pencarian ke session
  $_SESSION['search_keyword'] = $keyword;
} else {
  // Ambil data invoice items dengan pagination
  $invoiceItems = $invoiceItemsModel->getWithLimit($perPage, $offset, $id_inv);
}

$totalInvoiceItems = $invoiceItemsModel->getCountByInvoiceId($id_inv);
$totalPages = ceil($totalInvoiceItems / $perPage);

$dataSisa = $invoiceModel->getSisa($id_inv);
$sisa = $dataSisa['sisa'];


// Hitung total dan jumlah barang
$totalHarga = 0;
$totalQty = 0;
foreach ($invoiceItems as $item) {
    $totalHarga += $item['total'];
    $totalQty += $item['qty'];
}

$itemsModel = new Item($db);
$items = $itemsModel->getAll();


?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Invoice Items</title>
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
            <div class="row">
              <div class="col-md-6"><h3 class="mb-0">Detail Invoice</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataInvoice();?>">Data Invoice</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Table Invoices</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
          <!--begin::Container-->
          <div class="container-fluid">
          <div class="row g-4">
      <div class="col-md-12">
       <!-- notifikasi tambah -->
    <?php if (isset($_SESSION['alert'])): ?>
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['alert']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
<!-- end notifikasi tambah -->

<!-- notifikasi hapus -->
<?php if (isset($_SESSION['alert_delete'])): ?>
    <div class="alert alert-<?= $_SESSION['alert_delete']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['alert_delete']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert_delete']); ?>
<?php endif; ?>


<div class="container mt-2">
        <div class="card p-4">
          <h3 class="text-center mb-4"><span class="badge bg-primary"><i class="bi bi-info-circle-fill me-1"></i> Kode Invoice: <?= htmlspecialchars($invoice['kode_inv']) ?></span></h3>

          <!-- Info Invoice -->
          <div class="mb-3">
            <span class="badge bg-info"><i class="bi bi-calendar2-week-fill me-1"></i> Tanggal: <?= htmlspecialchars($invoice['tgl_inv']) ?></span>
          </div>
          <div class="mb-4">
            <span class="badge bg-primary"><i class="bi bi-person-circle me-1"></i> Customer: <?= htmlspecialchars($invoice['name']) ?></span>
          </div>

          <!-- 2 tombol -->
            <div class="d-flex justify-content-lg-start align-items-center mb-3">
            <!-- button create -->
            <div class="mb-2 me-2">
                <a href="<?= $BaseUrl->getUrlFormInvoice($id_inv) ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Customer
                </a>
            </div>
            <!-- end button create -->

            <!-- button print -->
            <div class="mb-2 mx-2">
                <a href="<?= $BaseUrl->getPrint($id_inv) ?>" class="btn btn-success btn-sm">
                <i class="bi bi-printer me-1"></i> Print
                </a>
            </div>
            <!-- end button print -->
            </div>


          <!-- Ringkasan -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <strong>Jumlah Barang:</strong> <?= count($invoiceItems) ?> &nbsp;&nbsp;
              <strong>Total Harga:</strong> Rp. <?= number_format($totalHarga, 0, ',', '.') ?>
            </div>
          </div>
          
          <?php if(!empty($invoice['note'])): ?>
          <div class="mb-3">
            <strong>Catatan:</strong>
              <div class="border p-2 bg-light rounded">
                <?= nl2br(htmlspecialchars($invoice['note'])) ?>
              </div>
          </div>
          <?php else: ?>
            <div class="mb-3">
            <strong>Catatan:</strong>
              <div class="border p-2 bg-light rounded">
                Tidak ada catatan
              </div>
          </div>
          <?php endif; ?>

          <!-- Tombol -->
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="<?= $BaseUrl->getUrlFormInvoiceItems($id_inv) ?>" class="btn btn-primary btn-sm">
              <i class="bi bi-plus-circle me-1"></i> Tambah Item
            </a>
          </div>

          <!-- Tabel Item -->
          <div class="table-responsive">
            <table class="table table-bordered align-middle table-striped">
              <thead class="table-light">
                <tr>
                  <th>No.</th>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th class="text-end">Qty</th>
                  <th class="text-end">Harga</th>
                  <th class="text-end">Total</th>
                  <th class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($invoiceItems)) : ?>
                  <?php $i=0; foreach ($invoiceItems as $item) : ?>
                    <tr>
                      <td><?= ++$i ?>.</td>
                      <td><?= htmlspecialchars($item['ref_no']) ?></td>
                      <td><?= htmlspecialchars($item['name']) ?></td>
                      <td class="text-end"><?= $item['qty'] ?></td>
                      <td class="text-end">Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                      <td class="text-end">Rp. <?= number_format($item['total'], 0, ',', '.') ?></td>
                      <td class="text-center">
                      <a href="<?= $BaseUrl->getUrlFormInvoiceItems( $id_inv, $item['id']) ?>" 
                        class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil-square"></i> Edit
                      </a>
                        <a href="<?= $BaseUrl->getUrlControllerDeleteInvoiceItems($item['id']) ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus item ini?');">
                            <i class="bi bi-trash"></i> Delete
                            </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <tr>
                    <td colspan="6" class="text-center">Belum ada item pada invoice ini.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

            </div>
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
