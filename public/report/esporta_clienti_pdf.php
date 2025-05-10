<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Twig\Loader\FilesystemLoader;
use Twig\Environment;

// Configura Twig
$loader = new FilesystemLoader(__DIR__ . '/../template');
$twig = new Environment($loader);

// Recupera i dati dall'API
$apiUrl = 'http://localhost/opalix_server/public/api/clienti';
$response = file_get_contents($apiUrl);
$clienti = json_decode($response, true);

$apiAziendaUrl = 'http://localhost/opalix_server/public/api/azienda';
$responseAzienda = file_get_contents($apiAziendaUrl);
$azienda = json_decode($responseAzienda, true);

if (!$clienti || !is_array($clienti)) {
    die('Errore nel recupero dei dati clienti.');
}

if (!$azienda || !is_array($azienda)) {
    die('Errore nel recupero dei dati dell\'azienda.');
}

// Renderizza il template
$html = $twig->render('clienti_template.html.twig', [
    'clienti' => $clienti,
    'azienda' => $azienda // Ora è un oggetto associativo, NON un array di oggetti
]);

// Genera il PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream('clienti.pdf', ['Attachment' => false]);
?>
