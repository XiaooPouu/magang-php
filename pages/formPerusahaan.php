<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/company.php';


$isEdit = false;

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $company = $companyModel->getCompanyById($id); // Mengambil data item berdasarkan id
    if ($company) {
        $isEdit = true;
    }
}
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Form Perusahaan</title>
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
              <div class="col-md-6"><h3 class="mb-0">Perusahaan Form</h3></div>
              <div class="col-md-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getInformasiPerusahaan();?>">Informasi Perusahaan</a></li>
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
                
                  <!--begin::Input Group-->
                <div class="card card-success card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title"><?= $isEdit ? 'Edit Perusahaan' : 'Input Perusahaan' ?></div>
                </div>
                  <!--end::Header-->
                  <form action="<?= $BaseUrl->getUrlControllerPerusahaan();?>" method="POST">
                   <?php if ($isEdit): ?>
                  <input type="hidden" name="id" value="<?= htmlspecialchars($company['id']) ?>">
                <?php endif; ?>
                  <!--begin::Body-->
                    <div class="card-body">
                      <div class="row">
                        <div class="col-md-6">
                          <label for="nama_perusahaan" class="form-label">Nama Perusahaan</label>
                          <div class="input-group mb-3">
                            <input
                              type="text"
                              class="form-control"
                              placeholder="Contoh: PT. ABC"
                              aria-label="Perusahaan"
                              name="nama_perusahaan"
                              id="nama_perusahaan"
                              aria-describedby="basic-addon1"
                              required
                              value="<?= $isEdit ? $company['nama_perusahaan'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="alamat" class="form-label">Alamat</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="alamat"
                              id="alamat"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: Jl. ABC"
                              value="<?= $isEdit ? $company['alamat'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="kota" class="form-label">Kota</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="kota"
                              id="kota"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: Jakarta"
                              value="<?= $isEdit ? $company['kota'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="provinsi" class="form-label">Provinsi</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="provinsi"
                              id="provinsi"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: DKI Jakarta"
                              value="<?= $isEdit ? $company['provinsi'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="kode_pos" class="form-label">Kode Pos</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="kode_pos"
                              id="kode_pos"
                              required
                              pattern="[0-9]+"
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: 12345"
                              value="<?= $isEdit ? $company['kode_pos'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="negara" class="form-label">Negara</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="negara"
                              id="negara"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: Indonesia"
                              value="<?= $isEdit ? $company['negara'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="telepon" class="form-label">Nomer Telephone</label>
                          <div class="mb-3">
                            <input
                              type="text"
                              class="form-control"
                              name="telepon"
                              id="telepon"
                              required
                              pattern="[0-9]+"
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: 08123456789"
                              value="<?= $isEdit ? $company['telepon'] : ''?>"
                            />
                          </div>
                        </div>

                        <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                          <div class="mb-3">
                            <input
                              type="email"
                              class="form-control"
                              name="email"
                              id="email"
                              required
                              aria-describedby="basic-addon3 basic-addon4"
                              placeholder="Contoh: 2bN0w@example.com"
                              value="<?= $isEdit ? $company['email'] : ''?>"
                            />
                          </div>
                        </div>
                        
                      </div>
                    </div>
                    <!--end::Body-->

                  <!--begin::Footer-->
                  <div class="card-footer d-flex align-items-center">
                          <a href="<?= $BaseUrl->getInformasiPerusahaan();?>" class="btn btn-secondary" style="padding: 8px 16px;">
                            <i class="bi bi-x-circle me-1"></i> Cancel</a>
                          <button type="submit" class="btn btn-success ms-auto" style="padding: 8px 16px;" name="<?= $isEdit ? 'update_perusahaan' : 'add_perusahaan'?>">
                            <i class="bi bi-check-circle-fill me-1"></i> Submit</button>
                    </div>
                  </form>
                  <!--end::Footer-->
                  </div>


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