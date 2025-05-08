<?php
require_once('../../../vendor/autoload.php'); // TCPDF via Composer

use TCPDF;

// 1. Richiama l’API
$apiUrl = "https://tuodominio.it/api/clienti"; // <-- cambia con la tua URL
$json = file_get_contents($apiUrl);
$data = json_decode($json, true);

if (!$data) {
    die("Errore nel recupero dati dall'API.");
}

// 2. Crea il PDF
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Il Tuo Gestionale');
$pdf->SetTitle('Elenco Clienti');
$pdf->SetMargins(10, 20, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

// 3. Crea tabella HTML
$html = '<h1>Elenco Clienti</h1><table border="1" cellpadding="4" cellspacing="0">';
$html .= '<tr>
    <th>Nome</th><th>Cognome</th><th>Codice Fiscale</th><th>Data Nascita</th>
    <th>Indirizzo</th><th>Città</th><th>CAP</th><th>Provincia</th><th>Paese</th>
    <th>Cellulare</th><th>Email</th>
</tr>';

foreach ($data as $cliente) {
    $html .= '<tr>';
    $html .= '<td>' . htmlspecialchars($cliente['nome'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['cognome'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['codice_fiscale'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['data_nascita'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['via'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['citta'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['cap'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['provincia'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['paese'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['cellulare'] ?? '') . '</td>';
    $html .= '<td>' . htmlspecialchars($cliente['email'] ?? '') . '</td>';
    $html .= '</tr>';
}

$html .= '</table>';

// 4. Stampa il PDF
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('clienti.pdf', 'D');
