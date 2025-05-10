<?php
// app/Controllers/ClientiController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class AziendaController
{
public function getAzienda()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                * 
            FROM configurazione_azienda 
            LIMIT 1
        ");

        $azienda = $stmt->fetch(PDO::FETCH_ASSOC); // <<< CAMBIATO QUI

        if (!$azienda) {
            sendJsonResponse(['message' => 'Nessuna azienda trovata'], 404);
        } else {
            sendJsonResponse($azienda); // <<< restituisce un oggetto JSON
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}


}
