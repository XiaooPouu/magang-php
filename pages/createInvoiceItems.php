<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/invoice_items.php';
include BASE_PATH . 'models/invoice.php';
include BASE_PATH . 'models/items.php';

$invoice_id = $_GET['id_inv'] ?? null;

$db = (new Database())->getConnection();

$model = new Invoice($db);
$invoiceData = $model->getById($invoice_id);
$itemsModel = new Item($db);
$items = $itemsModel->getAll();

// Validasi invoice
if (!$invoice_id || !$invoiceData) {
    die("Invoice tidak ditemukan.");
}

// Set session invoice_id
if (!isset($_SESSION['invoice_id']) && $invoice_id) {
    $_SESSION['invoice_id'] = $invoice_id;
}

$invoiceItemsModel = new InvoiceItems($db);
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Tambah Item ke Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css" />
</head>
<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
<div class="app-wrapper">
    <?php include BASE_PATH . 'includes/sidebar.php'; ?>

    <main class="app-main">
        <div class="container mt-4">
            <h3>Tambah Item ke Invoice #<?= $invoice_id ?></h3>

            <form action="<?= BASE_URL ?>controllers/invoice_itemsController.php" method="POST" class="card p-4 mt-3">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">

                <div class="mb-3">
                    <label for="item" class="form-label">Item</label>
                    <select name="items_id" class="form-select" required>
                        <option value="">-- Pilih Item --</option>
                        <?php foreach ($items as $item): ?>
                            <option value="<?= $item['id'] ?>">
                                <?= htmlspecialchars($item['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="qty" class="form-label">Qty</label>
                    <input type="number" name="qty" class="form-control" min="1" value="1" required>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Harga (opsional)</label>
                    <input type="number" name="price" class="form-control" placeholder="Kosongkan untuk default harga item">
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= BASE_URL ?>pages/dataInvoiceItems.php?id=<?= $invoice_id ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </main>

    <?php include BASE_PATH . 'includes/footer.php'; ?>
</div>
</body>
</html>
