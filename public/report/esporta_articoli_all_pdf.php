<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Recupera tutti gli articoli da API
$data = file_get_contents("http://localhost/opalix_server/public/api/articoli");
$articoli = json_decode($data, true);

if (!$articoli || !is_array($articoli)) {
    die("Errore nel recupero della lista degli articoli.");
}

$apiAziendaUrl = 'http://localhost/opalix_server/public/api/azienda';
$responseAzienda = file_get_contents($apiAziendaUrl);
$azienda = json_decode($responseAzienda, true);

// Configura Twig
$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../template');
$twig = new \Twig\Environment($loader);

// Render HTML con Twig
$html = $twig->render('articoli_template.html.twig', [
    'articoli' => $articoli,
    'azienda' => $azienda, // Ora è un oggetto associativo, NON un array di oggetti
    'data_generazione' => (new DateTime())->format('d/m/Y H:i')
]);

// Configura Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("lista_articoli.pdf", ["Attachment" => false]);
