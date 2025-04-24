<?php
session_start();
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include BASE_PATH . 'models/items_costumer.php';
include BASE_PATH . 'models/items.php';
include BASE_PATH . 'models/costumer.php';

$db = (new Database())->getConnection();
$itemModel = new Item($db);
$costumerModel = new Costumer($db);
$itemsCustomerModel = new ItemsCostumer($db);

$items = $itemModel->getAll();
$customers = $costumerModel->getAll();

if (!isset($_GET['id_ic']) || empty($_GET['id_ic'])) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'ID tidak ditemukan.'];
    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit;
}

$id = $_GET['id_ic'];
$data = $itemsCustomerModel->getById($id);

if (!$data) {
    $_SESSION['alert'] = ['type' => 'danger', 'message' => 'Data tidak ditemukan.'];
    header('Location: ' . BASE_URL . 'pages/dataItems_Costumer.php');
    exit;
}
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Edit Data Item Customer</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />
  <link rel="stylesheet" href="<?= BASE_URL ?>src/css/adminlte.css" />
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
  <div class="app-wrapper">
    <?php include BASE_PATH . 'includes/header.php'; ?>

    <?php include BASE_PATH . 'includes/sidebar.php'; ?>

    <main class="app-main">
      <div class="app-content-header">
        <div class="container-fluid">
          <!--begin::Container-->
          <div class="container-fluid mb-4">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Edit Items Costumers</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL?>pages/dataItems_Costumer.php">Data Items Costumers</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Edit Form</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
          <div class="row g-4">
            <div class="col-12">

              <?php if (isset($_SESSION['alert'])): ?>
                <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show" role="alert">
                  <?= $_SESSION['alert']['message'] ?>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION['alert']); ?>
              <?php endif; ?>

              <!--begin::Form Validation-->
              <div class="card card-info card-outline mb-4">
                  <!--begin::Header-->
                  <div class="card-header"><div class="card-title">Form Items Costumer</div></div>
                  <!--end::Header-->
                  <!--begin::Form-->
                  <form action="<?= BASE_URL ?>controllers/items_costumersController.php" method="POST">
                  <input type="hidden" name="id_ic" value="<?= $data['id_ic'] ?>">
                    <!--begin::Body-->
                    <div class="card-body">
                      <!--begin::Row-->
                      <div class="row g-3">
                       <!--begin::Col-->
                       <div class="col-md-6">
                          <label for="items_id" class="form-label">Nama Items</label>
                          <select class="form-select" name="items_id" id="items_id" required>
                          <option value="" disabled>-- Pilih Item --</option>
                    <?php foreach ($items as $item): ?>
                      <option value="<?= $item['id'] ?>" <?= ($data['id_items'] == $item['id']) ? 'selected' : '' ?>>
                        <?= $item['name'] ?>
                      </option>
                    <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-6">
                          <label for="customers_id" class="form-label">Nama Costumers</label>
                          <select class="form-select" name="customers_id" id="customers_id" required>
                          <option value="" disabled>-- Pilih Customer --</option>
                    <?php foreach ($customers as $cust): ?>
                      <option value="<?= $cust['id'] ?>" <?= ($data['id_customers'] == $cust['id']) ? 'selected' : '' ?>>
                        <?= $cust['name'] ?>
                      </option>
                    <?php endforeach; ?>
                          </select>
                          <div class="invalid-feedback">Please select a valid state.</div>
                        </div>
                        <!--end::Col-->
                        <!--begin::Col-->
                        <div class="col-md-6">
                          <label for="price" class="form-label">Harga</label>
                          <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">Rp.</span>
                            <input
                              type="number"
                              class="form-control"
                              id="price"
                              aria-describedby="inputGroupPrepend"
                              required
                              name="price"
                              value="<?=htmlspecialchars($data['price']);?>"
                            />
                            <div class="invalid-feedback">Please choose a username.</div>
                          </div>
                        </div>
                        <!--end::Col-->
                      </div>
                      <!--end::Row-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer d-flex justify-content-end">
                      <button type="submit" name="update_ic" class="btn btn-info">
                      <i class="bi bi-save me-1"></i> Update
                    </button>
                    <a href="<?= BASE_URL ?>pages/dataItems_Costumer.php" class="btn btn-secondary ms-2">← Kembali</a>
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
              </div>

            </div>
          </div>
        </div>
      </div>
    </main>

    <?php include BASE_PATH . 'includes/footer.php'; ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>
</body>
</html>
