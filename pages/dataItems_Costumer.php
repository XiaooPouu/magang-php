<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/items_costumer.php';
include BASE_PATH . 'models/items.php';
include BASE_PATH . 'models/costumer.php';

$db = (new Database())->getConnection();
$itemsCostumerModel = new ItemsCostumer($db);

$keyword = $_GET['search'] ?? '';

$page = isset($_GET['page']) ? $_GET['page'] : 1;
$perPage = 5;
$offset = ($page - 1) * $perPage;

// hitung total data
$totalItems_Costumers = $itemsCostumerModel->getCount();

// hitung total halaman
$totalPages = ceil($totalItems_Costumers / $perPage);


if (isset($_SESSION['search_data'])) {
  $data = $_SESSION['search_data'];
  unset($_SESSION['search_data']); // biar gak nyangkut terus
  unset($_SESSION['search_keyword']);
} else {
  $data = $itemsCostumerModel->getWithLimit($perPage, $offset);
}

$formDelete = isset($_SESSION['form_delete']) ? $_SESSION['form_delete'] : [];
$alert = isset($_SESSION['alert_delete']);

?>

<!doctype html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Data Items Customer</title>
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
    <?php include BASE_PATH . 'includes/header.php' ?>

      <?php include BASE_PATH . 'includes/sidebar.php' ?>

      <main class="app-main">
        <div class="app-content-header">
          <div class="container-fluid">
             <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Data Items Costumers</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL?>pages/dataItems_Costumer.php">Data Items Costumers</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tabel Items Costumers</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
            <div class="row g-4">
              <!-- untuk notif -->
              <div class="col-md-12">
                <!-- notif tambah -->
              <?php if (isset($_SESSION['alert'])): ?>
            <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['alert']['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>
        <!-- end notif tambah -->

              <!-- notif update -->
                  <?php if (isset($_SESSION['alert_update'])): ?>
                    <div class="alert alert-<?= $_SESSION['alert_update']['type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['alert_update']['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['alert_update']); ?>
                <?php endif; ?>
                <!-- end notif update -->

                <!-- notif delete -->
                <?php if (isset($_SESSION['alert_delete'])): ?>
                    <div class="alert alert-<?= $_SESSION['alert_delete']['type'] ?> alert-dismissible fade show" role="alert">
                        <?= $_SESSION['alert_delete']['message'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['alert_delete']); ?>
                <?php endif; ?>
              <!-- end notif delete -->
              </div>
                <!-- end untuk notif -->

              <!-- Search Form -->
              <form action="<?= BASE_URL ?>controllers/items_costumersController.php" method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search" value="<?= $_SESSION['search_keyword'] ?? '' ?>">
                <input type="hidden" name="page" value="<?= $page ?>">
                <button class="btn btn-primary m-2" type="submit">
                  Search</button>
                <a href="<?= BASE_URL ?>pages/dataItems_Costumer.php" class="btn btn-secondary m-2">Reset</a>
              </form>

              <!-- Table Items -->
                <div class="card mb-4">
                  <div class="card-header"><h3 class="card-title">Table Items Costumers</h3>
                </div>

                 <!-- Button Create -->
              <div class="mt-3 mx-3">
                <a href="<?= BASE_URL ?>pages/createItemsCostumer.php" class="btn btn-primary btn-sm">
                  <i class="bi bi-plus-circle me-1"></i>Create New</a>
              </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th>Items</th>
                          <th>Nama Customers</th>
                          <th>Harga</th>
                          <th >Action</th>
                        </tr>
                      </thead>
                      <?php foreach ($data as $row): ?>
                      <tbody>
                        <tr class="align-middle">
                          <td><?= htmlspecialchars($row['items_name'])?></td>
                          <td><?= htmlspecialchars($row['customers_name'])?></td>
                          <td>
                          <?= htmlspecialchars('Rp. ' . number_format($row['price'], 0,',','.'))?>
                          </td>
                          <td>
                          <?php if (isset($row['id_ic'])): ?>
                          <a href="<?= BASE_URL ?>pages/editItemsCostumer.php?id_ic=<?= htmlspecialchars($row['id_ic']) ?>" class="btn btn-sm btn-warning me-1">
                            <i class="bi bi-pencil-square"></i>Edit</a>
                          <a href="<?= BASE_URL ?>controllers/items_costumersController.php?delete_ic=<?= htmlspecialchars($row['id_ic']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                            <i class="bi bi-trash"></i>Delete</a>
                        <?php else: ?>
                          <span class="text-danger">ID not found</span>
                        <?php endif; ?>
                          </td>
                        </tr>
                        <?php endforeach;?>
                      </tbody>
                    </table>
                  </div>
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
            </div>
          </div>
        </div>
      </main>

      <?php include BASE_PATH . 'includes/footer.php' ?>
    </div>

    <!-- Scripts -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"
      crossorigin="anonymous"
    ></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector('.sidebar-wrapper');
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: 'os-theme-light',
              autoHide: 'leave',
              clickScroll: true,
            },
          });
        }
      });
    </script>
  </body>
</html>
