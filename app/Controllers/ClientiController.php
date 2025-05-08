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
                    c.note,
                    c.data_creazione,
                    i.via,
                    i.citta,
                    i.cap,
                    i.provincia,
                    i.paese,
                    ci.tipo AS indirizzo_tipo,
                    GROUP_CONCAT(CONCAT(ct.tipo, ': ', ct.valore, ' ', ct.etichetta) SEPARATOR '; ') AS contatti
                FROM clienti c
                LEFT JOIN cliente_indirizzo ci ON c.id = ci.cliente_id
                LEFT JOIN indirizzi i ON ci.indirizzo_id = i.id
                LEFT JOIN contatti ct ON c.id = ct.cliente_id
                GROUP BY c.id
                ORDER BY c.data_creazione DESC;
            ");
            $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($clienti)) {
                sendJsonResponse(['message' => 'Nessun cliente trovato.'], 200);
            } else {
                sendJsonResponse($clienti);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }

}
