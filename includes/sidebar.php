<?php
if (!defined('BASE_PATH')) {
  require_once __DIR__ . '/../env.php';
}
require_once BASE_PATH . 'function/baseurl.php';

?>

<!--begin::Sidebar-->
<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
  <!--begin::Sidebar Brand-->
  <div class="sidebar-brand">
    <a href="<?= $BaseUrl->getIndex(); ?>" class="brand-link">
      <img
        src="<?= $BaseUrl->getLogo(); ?>"
        alt="AdminLTE Logo"
        class="brand-image opacity-75 shadow"
      />
      <span class="brand-text fw-light">Wevelope</span>
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
          <a href="<?= $BaseUrl->getIndex(); ?>" class="nav-link">
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
              <a href="<?= $BaseUrl->getUrlDataItems(); ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Items</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BaseUrl->getUrlDataCostumer(); ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Costumers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BaseUrl->getUrlDataSupplier();?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Suppliers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= $BaseUrl->getUrlDataItemsCostumer(); ?>" class="nav-link">
                <i class="nav-icon bi bi-circle"></i>
                <p>Data Items Costumers</p>
              </a>
            </li>
          </ul>
        </li>

        <li class="nav-item">
          <a href="<?= $BaseUrl->getUrlDataInvoice(); ?>" class="nav-link">
            <i class="nav-icon bi bi-table"></i>
            <p>Tabel Invoice</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= BASE_URL ?>pages/dataomset.php?periode=harian" class="nav-link">
            <i class="nav-icon bi bi-wallet"></i>
            <p>Tabel Omset</p>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $BaseUrl->getUrlDataBestSeller(); ?>" class="nav-link">
            <i class="nav-icon bi bi-fire"></i>
            <p>Best Seller</p>
          </a>
        </li>
      </ul>
      <!--end::Sidebar Menu-->
    </nav>
  </div>
  <!--end::Sidebar Wrapper-->
</aside>
<!--end::Sidebar-->
