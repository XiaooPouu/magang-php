<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'config/database.php';
include_once BASE_PATH . 'models/kwitansi.php';
include_once BASE_PATH . 'models/company.php';


$company = $companyModel->getCompany();
$getNamePIC = $companyModel->getStatusPIC();
$kwitansi = $kwitansiModel->getKwitansi($_GET['id_payments']);

// ambil tahun saja
$tahun = date('Y', strtotime($kwitansi['tanggal']));

// ambil bulan dan tanggal
$bulanTanggal = date('md', strtotime($kwitansi['tanggal']));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }

        .kwitansi-container {
            border: 1px solid black;
            padding: 20px;
            width: 700px;
            margin: 0 auto;
        }

        .kwitansi-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .kwitansi-table {
            width: 100%;
            line-height: 1.8;
        }

        .kwitansi-table td {
            vertical-align: top;
        }

        .signature {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            text-align: center;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 50px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .bold {
            font-weight: bold;
        }

    </style>
</head>
<body onload="window.print();">
    <div class="kwitansi-container">
        <div class="kwitansi-title">KWITANSI PEMBAYARAN</div>
        <table class="kwitansi-table">
            <tr>
                <td>Sudah terima dari</td>
                <td>: <?= $company['nama_perusahaan'] ?? '-' ?></td>
            </tr>
            <tr>
                <td>Uang sejumlah</td>
                <td>: <span class="bold"><?= htmlspecialchars('Rp ' . number_format($kwitansi['nominal'], 0, ',', '.'))?>,-</span></td>
            </tr>
            <tr>
                <td>Untuk pembayaran</td>
                <td>: Invoice Kode: <?= $kwitansi['kode_inv'];?></td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: <?= $kwitansi['tanggal'];?></td>
            </tr>
            <tr>
                <td>No. Kwitansi</td>
                <td>: KW/<?= $tahun?>/<?= $bulanTanggal?>/<?= $kwitansi['id'];?></td>
            </tr>
        </table>

        <p class="bold">Total: <?= htmlspecialchars('Rp ' . number_format($kwitansi['nominal'], 0, ',', '.'))?>,-</p>

        <div class="signature">
            <div>
                <p>Penerima,</p>
                <br><br><br>
                <p>___________________<br><?= $kwitansi['name'];?></p>
            </div>
            <div>
                <p>Hormat Kami,</p>
                <br><br><br>
                <p>___________________<br><?= $getNamePIC['name'] ?? '-'?></p>
            </div>
        </div>

        <div class="footer">
            <?= $company['nama_perusahaan']?> - <?= $company['alamat']?>, <?= $company['kode_pos']?>, <?= $company['kota']?>, <?= $company['provinsi']?>, <?= $company['negara']?> | Telp: <?= $company['telepon']?> | <?= $company['email']?>
        </div>
    </div>
</body>
</html>
