<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Picqer\Barcode\BarcodeGeneratorPNG;

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Recupera dati articolo da API
    $data = file_get_contents("http://localhost/opalix_server/public/api/articoli/{$id}");
    $articolo = json_decode($data, true);

    if (!$articolo) {
        die("Errore nel recupero dati articolo.");
    }

    // Genera codice a barre
    $generator = new BarcodeGeneratorPNG();
    $barcode = base64_encode($generator->getBarcode($articolo['codice_articolo'], $generator::TYPE_CODE_128, 2, 80));

    // Configura Twig
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../template');
    $twig = new \Twig\Environment($loader);

    // Render HTML
    $html = $twig->render('articolo_template.html.twig', [
        'codice_articolo' => $articolo['codice_articolo'],
        'nome_articolo' => $articolo['nome_articolo'],
        'descrizione' => $articolo['descrizione'],
        'categoria' => $articolo['categoria'],
        'marca' => $articolo['marca'],
        'materiale' => $articolo['materiale'],
        'peso_materiale' => $articolo['peso_materiale'],
        'carati_materiale' => $articolo['carati_materiale'],
        'prezzo_acquisto' => $articolo['prezzo_acquisto'],
        'prezzo_vendita' => $articolo['prezzo_vendita'],
        'quantita' => $articolo['quantita'],
        'ubicazione' => $articolo['ubicazione'],
        'stato' => $articolo['stato'],
        'note' => $articolo['note'],
        'foto' => 'http://localhost/opalix_server/public/' . $articolo['foto'],
        'barcode_base64' => $barcode,
        'pietre' => is_array($articolo['pietre']) ? $articolo['pietre'] : []
    ]);

    // Genera PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("scheda_articolo_{$articolo['codice_articolo']}.pdf", ["Attachment" => false]);

} else {
    echo "ID mancante";
}
