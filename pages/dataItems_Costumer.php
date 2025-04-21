<?php
session_start();
require_once __DIR__ . '/../env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/items_costumer.php';
include BASE_PATH . 'models/items.php';
include BASE_PATH . 'models/costumer.php';

$db = (new Database())->getConnection();
$itemsCostumerModel = new ItemsCostumer($db);

$keyword = $_GET['search'] ?? '';

if (isset($_SESSION['search_data'])) {
  $data = $_SESSION['search_data'];
  unset($_SESSION['search_data']); // biar gak nyangkut terus
  unset($_SESSION['search_keyword']);
} else {
  $data = $itemsCostumerModel->getAll();
}
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
            <div class="row g-4">
              <div class="col-md-12">
                <div class="card-header border-0">
                  <h3 class="card-title">Items Customer</h3>
                </div>
              </div>

              <!-- Button Create -->
              <div class="mt-2">
                <a href="<?= BASE_URL ?>pages/createItemsCostumer.php" class="btn btn-primary btn-sm">Create New</a>
              </div>

              <!-- Search Form -->
              <form action="<?= BASE_URL ?>controllers/items_costumersController.php" method="GET" class="d-flex mb-3">
                <input type="text" name="search" class="form-control me-2" placeholder="Search" value="<?= $_SESSION['search_keyword'] ?? '' ?>">
                <button class="btn btn-primary m-2" type="submit">Search</button>
                <a href="<?= BASE_URL ?>pages/dataItems_Costumer.php" class="btn btn-secondary m-2">Reset</a>
              </form>

              <!-- Table Items -->
              <div class="col-lg-12">
                <div class="card mb-4">
                  <div class="card-header border-0">
                    <h3 class="card-title">Items Customer List</h3>
                  </div>
                  <div class="card-body table-responsive p-0">
                    <table class="table table-striped align-middle">
                      <thead>
                        <tr>
                          <th>Item</th>
                          <th>Customer</th>
                          <th>Harga</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php foreach ($data as $row): ?>
    <tr>
      <td><?= htmlspecialchars($row['items_name']) ?></td>
      <td><?= htmlspecialchars($row['customers_name']) ?></td>
      <td><?= htmlspecialchars('Rp. ' . number_format($row['price'], 2,',','.')) ?></td>
      <td>
  <?php if (isset($row['id_ic'])): ?>
    <a href="<?= BASE_URL ?>pages/editItemsCostumer.php?id_ic=<?= htmlspecialchars($row['id_ic']) ?>" class="btn btn-sm btn-warning me-1">Edit</a>
    <a href="<?= BASE_URL ?>controllers/items_costumersController.php?delete_ic=<?= htmlspecialchars($row['id_ic']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
  <?php else: ?>
    <span class="text-danger">ID not found</span>
  <?php endif; ?>
</td>

    </tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
              <!-- End Table -->
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
