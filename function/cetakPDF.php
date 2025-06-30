<?php
require_once __DIR__ . '/../config/env.php';
require_once BASE_PATH . 'function/baseurl.php';
require BASE_PATH . 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$id_inv = $_GET['id_inv'] ?? null;

// Jika mode "lihat" (tampil di browser)
if (isset($id_inv) && isset($_GET['lihat'])) {
    ob_start();
    include BASE_PATH . 'pages/invoice_print.php';
    $html = ob_get_clean();

    $options = new Options();
    $options->set('isRemoteEnabled', true);
    $options->set('default', 'Arial');

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream('invoice.pdf', ["Attachment" => false]); // tampil di browser
    exit;
}

// Jika mode "download"
else if (isset($id_inv) && isset($_GET['download'])) {
    ob_start();
    include BASE_PATH . 'pages/invoice_print.php';
    $html = ob_get_clean();

    $options2 = new Options();
    $options2->set('isRemoteEnabled', true);
    $options2->set('default', 'Arial');

    $dompdf1 = new Dompdf($options2);
    $dompdf1->loadHtml($html);
    $dompdf1->setPaper('A4', 'portrait');
    $dompdf1->render();
    $dompdf1->stream('invoice.pdf', ["Attachment" => true]); // langsung download
    exit;
} else {
    echo "ID invoice tidak ditemukan atau parameter tidak sesuai.";
    exit;
}
