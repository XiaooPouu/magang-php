<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/invoice.php';
require_once BASE_PATH . 'models/invoice_items.php';
require_once BASE_PATH . 'function/baseurl.php';
include_once BASE_PATH . 'models/company.php';

$id_inv = $_GET['id_inv'] ?? null;
if (!$id_inv) {
    echo "ID invoice tidak ditemukan.";
    exit;
}

$getInvoiceItems = $invoiceItems->getByInvoiceId($id_inv);
$getCompany = $companyModel->getCompany();
$getNamePIC = $companyModel->getStatusPIC();
$invoice = $invoiceModel->getById($id_inv);
$penandaTangan = $companyModel->getStatusPIC();

$total_invoice = 0;
foreach ($getInvoiceItems as $item){
  $total_invoice += $item['total'];
}

$logoUrl = $BaseUrl->getLogoPerusahaan() . $getCompany['logo'];
$tandaTanganUrl = $BaseUrl->getTandaTanganPerusahaan() . $getCompany['tanda_tangan'];

?>
<!DOCTYPE html>
<html>
<head>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    .logo {
      max-width: 100px;
    }
    .table-info td {
      vertical-align: top;
      padding: 5px;
    }
    .table-barang th, .table-barang td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
    }
    .text-end {
      text-align: right;
    }
    .text-left {
      text-align: left;
    }
    .text-center {
      text-align: center;
    }
    .no-border {
      border: none;
    }
    .signature-img {
      width: 80px;
    }
  </style>
</head>
<body>

<!-- Header: Logo dan Info Perusahaan -->
<table>
  <tr>
    <td style="width: 50%;">
      <img src="<?= $logoUrl?>" class="logo">
    </td>
    <td style="width: 50%;" class="text-end">
      <h2 style="font-size: 42px;">Invoice</h2>
      <table class="no-border" style="width: 100%; margin-top: 10px;">
        <tr><td class="text-left">Tanggal</td><td>: <?= htmlspecialchars(date('d-m-Y' , strtotime($invoice['tgl_inv'])))?></td></tr>
        <tr><td class="text-left">Tgl. Jatuh Tempo</td><td>: <?= htmlspecialchars(date('d-m-Y', strtotime($invoice['tgl_tempo'])))?></td></tr>
        <tr><td class="text-left">Ref. No.</td><td>: <?= htmlspecialchars($invoice['kode_inv'])?></td></tr>
      </table>
    </td>
  </tr>
</table>

<!-- Info Perusahaan dan Customer -->
<table class="table-info" style="margin-top: 20px;">
  <tr>
    <td style="width: 50%;">
      <strong>Informasi Perusahaan:</strong><br>
      <b><?= htmlspecialchars($getCompany['nama_perusahaan'])?></b><br>
      Alamat: <?= htmlspecialchars($getCompany['alamat'])?><br>
      <?= htmlspecialchars($getCompany['kota'])?>, <?= htmlspecialchars($getCompany['kode_pos'])?><br>
      <?= htmlspecialchars($getCompany['negara'])?><br>
      Telp: <?= htmlspecialchars($getCompany['telepon'])?><br>
      Email: <?= htmlspecialchars($getCompany['email'])?>
    </td>
    <td style="width: 50%;">
      <strong>Tagihan Untuk:</strong><br>
      <b><?= htmlspecialchars($invoice['name'])?></b><br>
      Telp: <?= htmlspecialchars($invoice['nomer'])?><br>
      Email: <?= htmlspecialchars($invoice['email'])?>
    </td>
  </tr>
</table>

<!-- Tabel Barang -->
<table class="table-barang" style="margin-top: 20px;">
  <thead>
    <tr>
      <th>No.</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Qty</th>
      <th>Harga</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $i = 0; foreach ($getInvoiceItems as $row):?>
    <tr>
      <td><?= ++$i?>.</td>
      <td><?= htmlspecialchars($row['ref_no'])?></td>
      <td><?= htmlspecialchars($row['name'])?></td>
      <td><?= htmlspecialchars($row['qty'])?></td>
      <td>Rp. <?= htmlspecialchars(number_format($row['price'], 0, ',', '.'))?></td>
      <td>Rp. <?= htmlspecialchars(number_format($row['total'], 0, ',', '.'))?></td>
    </tr>
    <?php endforeach;?>
  </tbody>
</table>

<!-- Total -->
<table style="width: 100%; margin-top: 10px;">
  <tr>
    <td style="width: 70%;"></td>
    <td style="width: 30%;">
      <table style="width: 100%;">
        <tr><td class="text-left">Subtotal</td><td class="text-end">Rp. <?= htmlspecialchars(number_format($total_invoice,0, ',', '.'))?></td></tr>
        <tr><td class="text-left"><strong>Total</strong></td><td class="text-end"><strong>Rp. <?=htmlspecialchars(number_format($total_invoice,0,',', '.'))?></strong></td></tr>
      </table>
    </td>
  </tr>
</table>

<!-- Footer: Keterangan dan Tanda Tangan -->
<table style="margin-top: 40px;">
  <tr>
    <td style="width: 60%; vertical-align: top;">
      <p><strong>Keterangan:</strong></p>
      <p>- <?= htmlspecialchars($invoice['note'])?></p>
      <br>
      <p><strong>Syarat dan Ketentuan:</strong></p>
    </td>
    <td style="width: 40%; text-align: center;">
      <p><?= htmlspecialchars(date('d F Y', strtotime($invoice['tgl_inv'])))?></p>
      <img src="<?=$tandaTanganUrl?>" class="signature-img"><br>
      <p><strong><?= htmlspecialchars($penandaTangan['name'])?></strong></p>
    </td>
  </tr>
</table>

</body>
</html>

