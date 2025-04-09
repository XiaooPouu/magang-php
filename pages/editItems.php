<?php
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/items.php';

    $database = new Database();
    $db = $database->getConnection();
    $itemModel = new Item($db);

    $id = $_GET['id'];
    $item = $itemModel->getById($id);

    if (!$item) {
        header("Location: ../pages/read.php");
        echo "Items not found!";
        exit();
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit items</title>
    <link rel="stylesheet" href="../src/css/adminlte.css">
</head>
<body>
<!-- Form Edit Item -->
<div class="card card-info card-outline mb-4">
    <div class="card-header"><div class="card-title">Edit Item</div></div>
    <form action="../controllers/itemsController.php" method="POST">
      <input type="hidden" name="id" value="<?= $item['id'] ?>">
      <div class="card-body row g-3">
        <div class="col-md-4">
          <label for="item_ref_no" class="form-label">REF NO</label>
          <input type="text" name="ref_no" class="form-control" id="item_ref_no" value="<?= htmlspecialchars($item['ref_no']) ?>" required>
        </div>
        <div class="col-md-4">
          <label for="item_name" class="form-label">Name</label>
          <input type="text" name="name" class="form-control" id="item_name" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>
        <div class="col-md-4">
          <label for="item_price" class="form-label">Price</label>
          <input type="number" name="price" class="form-control" id="item_price" value="<?= htmlspecialchars($item['price']) ?>" required>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" name="update_item" class="btn btn-info">Update Item</button>
        <a href="read.php" class="btn btn-secondary">Cancel</a>
      </div>
    </form>
  </div>

</div>




      <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-beta3/dist/js/adminlte.min.js"></script>

</body>
</html>