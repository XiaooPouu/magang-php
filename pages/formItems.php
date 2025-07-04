<?php
session_start();
require_once __DIR__ . '/../config/env.php';
include BASE_PATH . 'models/items.php';
require_once BASE_PATH . 'function/baseurl.php';

$formData = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : ['ref_no' => '', 'name' => '', 'price' => ''];
$alert = isset ($_SESSION['alert']);

$isEdit = false; // Variabel untuk menandakan mode edit
$item = null;

// Mengecek apakah ada parameter 'id' pada URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $itemModel = new Item($db);
    $item = $itemModel->getById($id); // Mengambil data item berdasarkan id
    if ($item) {
        $formData = [
            'ref_no' => $item['ref_no'],
            'name' => $item['name'],
            'price' => $item['price']
        ];
        $isEdit = true; // Menandakan form ini untuk edit
    }
}
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?= $isEdit ? 'Edit Item' : 'Add Item' ?></title>
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
              <div class="col-md-6"><h3 class="mb-0">Items Form</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getUrlDataItems();?>">Data Items</a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Form' : 'Input Form' ?></li>
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

      <?php if ($alert): ?>
  <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
    <?= $_SESSION['alert']['message'] ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
  <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
      
                <!--begin::Input Group-->
                <div class="card card-success card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title"><?= $isEdit ? 'Edit Item' : 'Input Items' ?></div>
                </div>
                  <!--end::Header-->
                  <form action="<?= $BaseUrl->getUrlControllerItems();?>" method="POST">
                   <?php if ($isEdit): ?>
                  <input type="hidden" name="id" value="<?= htmlspecialchars($item['id']) ?>">
                <?php endif; ?>
                  <!--begin::Body-->
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-4">
                          <label for="item_ref_no" class="form-label">Kode Items</label>
                          <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Contoh: ITM001"
                              aria-label="Ref_No"
                              name="ref_no"
                              id="item_ref_no"
                              aria-describedby="basic-addon1"
                              required
                              value="<?= htmlspecialchars($formData['ref_no'])?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-4">
                        <label for="item_name" class="form-label">Nama Items</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="name"
                              id="item_name"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Masukkan Nama Item Dengan Jelas"
                              value="<?= htmlspecialchars($formData['name'])?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-4">
                        <label for="item_price" class="form-label">Harga Items</label>
                          <div class="input-group mb-3">
                            <span class="input-group-text">Rp.</span>
                            <input
                              type="number"
                              class="form-control"
                              aria-label="Amount (to the nearest dollar)"
                              name="price"
                              id="item_price"
                              required
                              placeholder="Masukkan Angka Tanpa Pemisah Ribuan" 
                              value="<?= htmlspecialchars($formData['price'])?>"
                            />
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--end::Body-->

                  <!--begin::Footer-->
                  <div class="card-footer d-flex align-items-center">
                          <a href="<?= $BaseUrl->getUrlDataItems();?>" class="btn btn-secondary" style="padding: 8px 16px;">
                            <i class="bi bi-x-circle me-1"></i> Cancel</a>
                          <button type="submit" class="btn btn-success ms-auto" style="padding: 8px 16px;" name="<?= $isEdit ? 'update_item' : 'add_item'?>">
                            <i class="bi bi-check-circle-fill me-1"></i> Submit</button>
                    </div>
                  </form>
                  <!--end::Footer-->
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



