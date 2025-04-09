<?php
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/../models/costumer.php';

    $database = new Database();
    $db = $database->getConnection();
    $CostumerModel = new Costumer($db);

    $id = $_GET['id'];
    $customer = $CostumerModel->getById($id);

    if (!$customer) {
        header("Location: ../pages/read.php");
        echo "Customer not found!";
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
<!-- Form Edit Customer -->
<div class="card card-success card-outline mb-4">
    <div class="card-header"><div class="card-title">Edit Customer</div></div>
    <form action="../controllers/costumersController.php" method="POST">
      <input type="hidden" name="id" value="<?= $customer['id'] ?>">
      <div class="card-body row g-3">
        <div class="col-md-6">
          <label for="customer_ref_no" class="form-label">REF NO</label>
          <input type="text" name="ref_no" class="form-control" id="customer_ref_no" value="<?= htmlspecialchars($customer['ref_no']) ?>" required>
        </div>
        <div class="col-md-6">
          <label for="customer_name" class="form-label">Name</label>
          <input type="text" name="name" class="form-control" id="customer_name" value="<?= htmlspecialchars($customer['name']) ?>" required>
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" name="update_costumer" class="btn btn-success">Update Customer</button>
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