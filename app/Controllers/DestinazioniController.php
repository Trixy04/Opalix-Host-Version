<?php
// app/Controllers/DestinazioniController.php

class DestinazioniController {
    public function getDestinazioni()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT id, nome, descrizione
                FROM destinazione_scarichi
                ORDER BY nome ASC
            ");

            $destinazioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($destinazioni)) {
                sendJsonResponse(['message' => 'Nessuna destinazione trovata']);
            } else {
                sendJsonResponse($destinazioni);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
