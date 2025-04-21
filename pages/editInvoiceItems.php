<?php
session_start();
require_once __DIR__ . "/../config/config.php";
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/items.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);
$items = $itemModel->getAll();

$invoiceItemsModel = new InvoiceItems($db);

$id = $_GET['id'] ?? null;
$id_inv = $_GET['id_inv'] ?? $_SESSION['invoice_id'] ?? null;


// Simpan ke session biar konsisten
if ($id_inv) {
    $_SESSION['invoice_id'] = $id_inv;
} else {
    $id_inv = $_SESSION['invoice_id'] ?? null;
}

if (!$id || !$id_inv) {
    die("Parameter tidak lengkap.");
}

$itemData = $invoiceItemsModel->getById($id);
if (!$itemData) {
    die("Data tidak ditemukan.");
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Edit Invoice Item</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous" />
  <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <nav class="app-header navbar navbar-expand bg-body">
      <div class="container-fluid"></div>
    </nav>

    <?php include BASE_PATH . 'includes/sidebar.php'; ?>

    <main class="app-main">
      <div class="container-fluid py-4">
        <div class="row">
          <div class="col-lg-8 mx-auto">

            <!-- Form Edit Invoice Item -->
            <div class="card card-primary card-outline shadow-sm">
              <div class="card-header">
                <h5 class="card-title mb-0">Edit Item Invoice</h5>
              </div>
              <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>controllers/invoice_itemsController.php">
                  <input type="hidden" name="action" value="update">
                  <input type="hidden" name="id" value="<?= $itemData['id'] ?>">
                  <input type="hidden" name="invoice_id" value="<?= $id_inv ?>">

                  <div class="mb-3">
                    <label for="items_id" class="form-label">Nama Item</label>
                    <select name="items_id" id="items_id" class="form-select" required>
                      <?php foreach ($items as $item): ?>
                        <option value="<?= $item['id'] ?>" <?= $item['id'] == $itemData['items_id'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($item['ref_no']) ?> - <?= htmlspecialchars($item['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-3">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="number" name="qty" id="qty" value="<?= $itemData['qty'] ?>" class="form-control" required>
                  </div>

                  <div class="mb-3">
                    <label for="price" class="form-label">Harga (kosongkan untuk default)</label>
                    <input type="number" name="price" id="price" value="<?= $itemData['price'] ?>" class="form-control">
                  </div>

                  <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>pages/dataInvoiceItems.php?id_inv=<?= $id_inv ?>" class="btn btn-secondary">
                      <i class="bi bi-arrow-left-circle me-1"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                      <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                  </div>
                </form>
              </div>
            </div>
            <!-- End Form -->

          </div>
        </div>
      </div>
    </main>

    <?php include BASE_PATH . 'includes/footer.php'; ?>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js" crossorigin="anonymous"></script>

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
</body>
</html>
