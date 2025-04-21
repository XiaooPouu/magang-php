<?php
require_once __DIR__ . '/../env.php';
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/invoice.php';
require_once BASE_PATH . 'models/invoice_items.php';

$id_inv = $_GET['id'] ?? null;
if (!$id_inv) {
    echo "ID invoice tidak ditemukan.";
    exit;
}

$db = (new Database())->getConnection();
$invoiceModel = new Invoice($db);
$itemsModel = new InvoiceItems($db);

$data_invoice = $invoiceModel->getById($id_inv);
$data_items = $itemsModel->getAll();

$total_invoice = 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Print Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            color: #000;
        }

        h2 {
            text-align: center;
        }

        .info {
            margin-bottom: 20px;
        }

        .info strong {
            display: inline-block;
            width: 120px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        .footer {
            margin-top: 60px;
            text-align: center;
        }

        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }

            table, th, td {
                font-size: 12pt;
            }

            .footer {
                page-break-after: always;
            }
        }
    </style>
</head>
<body onload="window.print();">

    <!-- <div class="no-print">
        <a href="#" onclick="window.print()" style="padding: 10px 15px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px;">
            Print Invoice
        </a>
    </div> -->

    <h2>INVOICE</h2>

    <div class="info">
        <p><strong>Kode Invoice:</strong> <?= htmlspecialchars($data_invoice['kode_inv']) ?></p>
        <p><strong>Tanggal:</strong> <?= htmlspecialchars($data_invoice['tgl_inv']) ?></p>
        <p><strong>Customer:</strong> <?= htmlspecialchars($data_invoice['name']) ?></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Qty</th>
                <th>Harga</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($data_items as $item): ?>
            <?php if ($item['invoice_id'] == $id_inv): ?>
                <tr>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td><?= $item['qty'] ?></td>
                    <td><?= number_format($item['price'], 3, ',', '.') ?></td>
                    <td><?= number_format($item['total'], 3, ',', '.') ?></td>
                </tr>
                <?php $total_invoice += $item['total']; ?>
            <?php endif; ?>
        <?php endforeach; ?>
            <tr>
                <td colspan="3" class="text-right"><strong>Total</strong></td>
                <td><strong><?= number_format($total_invoice, 3, ',', '.') ?></strong></td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <p>Terima kasih atas pembelian Anda.</p>
    </div>
</body>
</html>
