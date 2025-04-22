<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . "models/invoice.php";
include BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/costumer.php';
include BASE_PATH . 'models/items.php';

$db = (new Database())->getConnection();

$id_inv = $_GET['id_inv'] ?? $_SESSION['invoice_id'] ?? null;


$invoiceModel = new Invoice($db);
$invoice = $invoiceModel->getById($id_inv);
if (!$invoice) {
    die("Data invoice tidak ditemukan.");
}

$invoiceItemsModel = new InvoiceItems($db);
$invoiceItems = $invoiceItemsModel->getByInvoiceId($id_inv);

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
<head>
  <meta charset="utf-8">
  <title>Detail Invoice</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Fonts & Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include BASE_PATH . 'includes/sidebar.php'; ?>
    
    <main class="app-main">
      <div class="container mt-5">
        <div class="card p-4">
          <h3 class="text-center mb-4">Detail Invoice</h3>

          <!-- Info Invoice -->
          <div class="mb-3">
            <span class="badge bg-primary"><i class="bi bi-info-circle-fill me-1"></i> Kode Invoice: <?= htmlspecialchars($invoice['kode_inv']) ?></span>
          </div>
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
                <a href="<?= BASE_URL ?>pages/editInvoice.php?id_inv=<?= $id_inv ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square me-1"></i> Edit Customer
                </a>
            </div>
            <!-- end button create -->

            <!-- button print -->
            <div class="mb-2 mx-2">
                <a href="<?= BASE_URL ?>pages/invoice_print.php?id=<?= $_GET['id_inv']?>" class="btn btn-success btn-sm">
                <i class="bi bi-printer me-1"></i> Print
                </a>
            </div>
            <!-- end button print -->
            </div>


          <!-- Ringkasan -->
          <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
              <strong>Jumlah Barang:</strong> <?= $totalQty ?> &nbsp;&nbsp;
              <strong>Total Harga:</strong> Rp. <?= number_format($totalHarga, 0, ',', '.') ?>
            </div>
          </div>

          <!-- Tombol -->
          <div class="mb-3 d-flex justify-content-between align-items-center">
            <a href="<?= BASE_URL ?>pages/createInvoiceItems.php?id_inv=<?= $invoice['id_inv'] ?>" class="btn btn-primary btn-sm">
              <i class="bi bi-plus-circle me-1"></i> Tambah Item
            </a>
            <form class="d-flex" method="GET" action="">
              <input type="hidden" name="id_inv" value="<?= htmlspecialchars($id_inv) ?>">
              <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search item...">
              <button class="btn btn-secondary btn-sm" type="submit">Cari</button>
            </form>
          </div>

          <!-- Tabel Item -->
          <div class="table-responsive">
            <table class="table table-bordered align-middle table-striped">
              <thead class="table-light">
                <tr>
                  <th>Kode Barang</th>
                  <th>Nama Barang</th>
                  <th>Qty</th>
                  <th>Harga</th>
                  <th>Total</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($invoiceItems)) : ?>
                  <?php foreach ($invoiceItems as $item) : ?>
                    <tr>
                      <td><?= htmlspecialchars($item['ref_no']) ?></td>
                      <td><?= htmlspecialchars($item['name']) ?></td>
                      <td><?= $item['qty'] ?></td>
                      <td>Rp. <?= number_format($item['price'], 0, ',', '.') ?></td>
                      <td>Rp. <?= number_format($item['total'], 0, ',', '.') ?></td>
                      <td>
                      <a href="<?= BASE_URL ?>pages/editInvoiceItems.php?id=<?= $item['id'] ?>&id_inv=<?= $invoice['id_inv'] ?>" 
   class="btn btn-warning btn-sm">
   <i class="bi bi-pencil-square"></i>
</a>


                        <a href="<?= BASE_URL?>controllers/invoice_itemsController.php?action=delete&id=<?= $item['id'] ?>" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin ingin menghapus item ini?');">
                            <i class="bi bi-trash"></i>
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
    </main>

    <?php include BASE_PATH . 'includes/footer.php'; ?>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>
</body>
</html>
