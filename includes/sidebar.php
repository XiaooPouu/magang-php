<?php
if (!defined('BASE_PATH')) {
  require_once __DIR__ . '/../config/config.php';
}
?>

<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <a href="<?= BASE_URL ?>index.php" class="brand-link">
      <img
        src="<?= BASE_URL ?>dist/assets/img/AdminLTELogo.png"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow"
      />
      <span class="brand-text fw-light">AdminLTE 4</span>
    </a>
  </div>
  <!--end::Sidebar Brand-->

  <!--begin::Sidebar Wrapper-->
  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <!--begin::Sidebar Menu-->
      <ul
        class="nav sidebar-menu flex-column"
        data-lte-toggle="treeview"
        role="menu"
        data-accordion="false"
      >
        <li class="nav-item">
          <a href="<?= BASE_URL ?>index.php" class="nav-link">
            <i class="nav-icon bi bi-speedometer"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>
              Tables
              <i class="nav-arrow bi bi-chevron-right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= BASE_URL ?>pages/dataItems.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Items</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>pages/dataCostumer.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Costumers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>pages/dataSuppliers.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Suppliers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= BASE_URL ?>pages/dataItems_Costumer.php" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Items Costumers</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="<?= BASE_URL ?>pages/dataInvoice.php" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Tabel Invoice</p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
