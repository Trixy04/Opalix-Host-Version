<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Recupero dati scarico
    $scaricoData = file_get_contents("http://localhost/opalix_server/public/api/scarichi/{$id}");
    $scarico = json_decode($scaricoData, true);

    if (!$scarico) {
        die("Errore nel recupero dei dati dello scarico.");
    }

    // Recupero dati azienda
    $apiAziendaUrl = 'http://localhost/opalix_server/public/api/azienda';
    $responseAzienda = file_get_contents($apiAziendaUrl);
    $azienda = json_decode($responseAzienda, true);

    if (!$azienda) {
        die("Errore nel recupero dei dati aziendali.");
    }

    // Configura Twig
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../template');
    $twig = new \Twig\Environment($loader);

    // Render HTML
    $html = $twig->render('scarico_template.html.twig', [
        'azienda' => $azienda,
        'scarico' => $scarico
    ]);

    // Genera PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("report_scarico_{$scarico['numero_documento']}.pdf", ["Attachment" => false]);

} else {
    echo "ID scarico mancante.";
}
