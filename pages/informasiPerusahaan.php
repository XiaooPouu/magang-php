<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/company.php';

$company = $companyModel->getCompany();
$getStatusPIC = $companyModel->getStatusPIC();

$logoPath = $company['logo'] ?? null;
$ttdPath = $company['tanda_tangan'] ?? null;
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Informasi Perusahaan</title>
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
              <div class="col-sm-6"><h3 class="mb-2">Informasi Perusahaan</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= $BaseUrl->getInformasiPerusahaan();?>">Informasi Perusahaan</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Table Informasi Perusahaan</li>
                </ol>
              </div>
            </div>
            <div class="col-md-12">
       

    <!-- notifikasi tambah berhasil -->
  <?php if (isset($_SESSION['alert'])): ?>
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
        <?= $_SESSION['alert']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>
<!-- end notifikasi tambah -->
      </div>

        <div class="content-wrapper">

 <!-- Main content -->
<section class="content">
  <div class="row justify-content-start">
    <div class="col-md-6">
      <!-- Card Box 1: Detail Perusahaan -->
      <div class="card">
        <div class="card-header bg-info d-flex justify-content-between align-items-center">
          <h3 class="card-title mb-0">Detail Pengaturan</h3>
        </div>
        <div class="card-body">
          <table class="table table-borderless">
            <tr>
              <th style="width: 200px;">Nama Perusahaan</th>
              <td><?= $company['nama_perusahaan'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>PIC</th>
              <td><?= $getStatusPIC['name'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td><?= $company['alamat'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Kota</th>
              <td><?= $company['kota'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Provinsi</th>
              <td><?= $company['provinsi'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Kode Pos</th>
              <td><?= $company['kode_pos'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Negara</th>
              <td><?= $company['negara'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Telepon</th>
              <td><?= $company['telepon'] ?? '-' ?></td>
            </tr>
            <tr>
              <th>Email</th>
              <td><?= $company['email'] ?? '-' ?></td>
            </tr>
          </table>
          <div class="text-end mt-lg-3">
            <a href="<?= $BaseUrl->getUrlFormPerusahaan($company['id']);?>" class="btn btn-outline-primary rounded-pill px-4">
              <i class="bi bi-pencil"></i> Ubah
            </a>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6">
  <!-- Card Box 2: Logo & Tanda Tangan -->
  <div class="card">
    <div class="card-header bg-info">
      <h3 class="card-title mb-0">Logo & Tanda Tangan Dokumen</h3>
    </div>
    <div class="card-body">
      <div class="row mb-3 g-3">
        <div class="col-6">
          <label class="form-label">Logo Perusahaan</label>
          <div class="border border-secondary rounded text-center flex-column d-flex align-items-center justify-content-center bg-white"
               style="width: 100%; height: 250px; overflow: hidden;">
            <?php if ($logoPath) :?>
              <img src="<?= $BaseUrl->getLogoPerusahaan() . htmlspecialchars($logoPath); ?>" alt="Logo Perusahaan"
                   style="width: 300px; height: 200px; object-fit: contain;">
            <?php else :?>
              <div class="small">Belum ada dokumen</div>
              <div class="small">Maksimal ukuran 20MB JPEG, PNG</div>
              <div class="small">Rekomendasi ukuran 300x200</div>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-6">
          <label class="form-label">Tanda Tangan</label>
          <div class="border border-secondary rounded d-flex text-center flex-column align-items-center justify-content-center bg-white"
               style="width: 100%; height: 250px; overflow: hidden;">
            <?php if($ttdPath) :?>
              <img src="<?= $BaseUrl->getTandaTanganPerusahaan() . htmlspecialchars($ttdPath); ?>" alt="Logo Perusahaan"
                   style="width: 300px; height: 200px; object-fit: contain;">
            <?php else:?>
              <div class="small">Belum ada dokumen</div>
              <div class="small">Maksimal ukuran 20MB JPEG, PNG</div>
              <div class="small">Rekomendasi ukuran 300x200</div>
            <?php endif ;?>
          </div>
        </div>
      </div>
      <div class="text-end">
        <a href="<?= $BaseUrl->getUrlFormPerusahaanLogoTTD($company['id']);?>" class="btn btn-outline-primary rounded-pill px-4">
          <i class="bi bi-pencil"></i> Ubah
        </a>
      </div>
    </div>
  </div>
</div>
  </div>
</section>


</div>

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