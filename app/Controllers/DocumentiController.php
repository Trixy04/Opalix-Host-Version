<?php
// app/Controllers/DocumentiController.php

class DocumentiController {
    public function getDoc()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT id, codice, descrizione
                FROM tipologie_documenti
                ORDER BY codice ASC
            ");

            $doc = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($doc)) {
                sendJsonResponse(['message' => 'Nessuna tipologia di documenti trovata']);
            } else {
                sendJsonResponse($doc);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
