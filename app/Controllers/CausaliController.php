<?php
// app/Controllers/CausaliController.php

class CausaliController {
    public function getCausali()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT id, codice, descrizione
                FROM causali_carico
                ORDER BY codice ASC
            ");

            $causali = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($causali)) {
                sendJsonResponse(['message' => 'Nessuna causale trovata']);
            } else {
                sendJsonResponse($causali);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
