<?php
// app/Controllers/ClientiController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class ClientiController
{
    public function getClienti()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                c.id,
                c.nome,
                c.cognome,
                c.codice_fiscale,
                c.data_nascita,
                c.data_creazione,
                i.via,
                i.citta,
                i.cap,
                i.provincia,
                i.paese,
                ct.cellulare,
                ct.email
            FROM clienti c
            LEFT JOIN clienti_indirizzi ci ON c.id = ci.cliente_id
            LEFT JOIN indirizzi i ON ci.indirizzo_id = i.id
            LEFT JOIN clienti_contatti cc ON c.id = cc.cliente_id
            LEFT JOIN contatti ct ON cc.contatto_id = ct.id
            ORDER BY c.id DESC
        ");

        $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clienti)) {
            sendJsonResponse(['message' => 'Nessun cliente trovato']);
        } else {
            sendJsonResponse($clienti);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

}
