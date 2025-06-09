<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/invoice.php';
require_once BASE_PATH . 'models/invoice_items.php';
include_once BASE_PATH . 'models/company.php';

$id_inv = $_GET['id'] ?? null;
if (!$id_inv) {
    echo "ID invoice tidak ditemukan.";
    exit;
}

$getInvoiceItems = $invoiceItems->getByInvoiceId($id_inv);
$company = $companyModel->getCompany();
$getNamePIC = $companyModel->getStatusPIC();
$data_invoice = $invoiceModel->getById($id_inv);


$total_invoice = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    .invoice-box {
      margin: 0 auto;
      max-width: 800px;
      border: 1px solid #eee;
      padding: 30px;
      line-height: 24px;
      color: #555;
    }
    .invoice-top {
  display: flex;
  justify-content: space-between;
  margin-bottom: 30px;
}
.from-section {
  font-size: 14px;
  line-height: 1.6;
}
.invoice-label h1 {
  font-size: 36px;
  margin: 0;
  color: black;
}
.invoice-info-block {
  display: flex;
  justify-content: space-between;
  margin-bottom: 30px;
}
.bill-to {
  font-size: 14px;
  line-height: 1.6;
}
.invoice-details table {
  font-size: 14px;
}
.invoice-details td {
  padding: 5px 10px;
}
    table {
      width: 100%;
      line-height: inherit;
      text-align: left;
      border-collapse: collapse;
    }
    table th {
      background: black;
      padding: 10px;
      color: #fff;
    }
    .harga-table , .total-table {
      text-align: right;
    }
    table td {
      padding: 10px;
      border-bottom: 1px solid #eee;
    }
    .total-section {
      margin-top: 20px;
      float: right;
      width: 300px;
    }
    .total-section table td {
      border: none;
    }

    .footer {
        display: flex;
        justify-content: space-between;
        margin-top: 40px;
    }

    .footer .terms {
        text-align: right;
    }

    .notes i, .terms i {
        font-size: 14px;
    }

    @media print {
    body {
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }

    table th {
      background: #000 !important;
      color: #fff !important;
      -webkit-print-color-adjust: exact !important;
      print-color-adjust: exact !important;
    }
  }
  </style>
</head>
<body onload="window.print();">
  <div class="invoice-box">

    <div class="invoice-top">
  <div class="from-section">
    <p><?= $company['nama_perusahaan'];?></p>
    <p><?= $getNamePIC['name'] ?? 'Belum ada PIC yang digunakan'?> (PIC)</p>
    <p><strong>Phone PIC:</strong> <?= $getNamePIC['nomer'];?></p>
    <p><strong>Email PIC:</strong> <?= $getNamePIC['email'];?></p>
    <p><?= $company['alamat'];?></p>
    <p><?= $company['kota'];?>, <?= $company['provinsi'];?></p>
    <p><?= $company['negara'];?></p>
  </div>
  <div class="invoice-label">
    <h1>INVOICE</h1>
  </div>
</div>

<div class="invoice-info-block">
  <div class="bill-to">
    <strong>Bill To:</strong><br>
    <?= $data_invoice['name'];?><br>
    <?= $data_invoice['alamat'];?><br>
    <?= $data_invoice['nomer'];?><br>
    <?= $data_invoice['email'];?><br>
  </div>
  <div class="invoice-details">
    <table>
      <tr>
        <td><strong>Invoice#</strong></td>
        <td><?= $data_invoice['kode_inv'];?></td>
      </tr>
      <tr>
        <td><strong>Tanggal Invoice</strong></td>
        <td><?= $data_invoice['tgl_inv'];?></td>
      </tr>
      <tr>
        <td><strong>Tanggal Jatuh Tempo</strong></td>
        <td><?= $data_invoice['tgl_tempo'];?></td>
      </tr>
    </table>
  </div>
</div>


    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Kode Item</th>
          <th>Nama Item</th>
          <th>Qty</th>
          <th class="harga-table">Harga</th>
          <th class="total-table">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0; foreach ($getInvoiceItems as $row) : ?>
        <tr>
          <td><?= ++$i;?>.</td>
          <td><?= $row['ref_no'];?></td>
          <td><?= $row['name'];?></td>
          <td><?= $row['qty'];?></td>
          <td class="harga-table"><?= htmlspecialchars('Rp. ' . number_format($row['price'], 0, ',', '.'))?></td>
          <td class="total-table"><?= htmlspecialchars('Rp. ' . number_format($row['total'], 0, ',', '.'))?></td>
        </tr>
        <?php $total_invoice += $row['total'];?>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="total-section">
      <table>
        <tr>
          <td><strong>TOTAL:</strong></td>
          <td><strong><?= htmlspecialchars('Rp. ' . number_format($total_invoice, 0, ',', '.'))?></strong></td>
        </tr>
      </table>
    </div>

    <div style="clear: both;"></div>

    <div class="footer">
    <div class="notes">
      <strong>Notes</strong><br>
      <i>Terima Kasih Atas Pembelian Anda</i><br>
      <i><?= $data_invoice['note'];?></i>
    </div>

    <div class="terms">
      <strong>Terms & Conditions</strong><br>
      <i>Harap lakukan pembayaran sebelum tanggal jatuh tempo.</i>
    </div>
    </div>
  </div>
</body>
</html>
