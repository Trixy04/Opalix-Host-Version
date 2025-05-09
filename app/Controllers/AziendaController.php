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

        $azienda = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($azienda)) {
            sendJsonResponse(['message' => 'Nessun cliente trovato']);
        } else {
            sendJsonResponse($azienda);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

}
