<?php
// app/Controllers/Pietre.php

class PietreController {
    public function getPietre()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                *
            FROM pietre
        ");

        $pietre = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($pietre)) {
            sendJsonResponse(['message' => 'Nessuna pietra trovata']);
        } else {
            sendJsonResponse($pietre);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



}