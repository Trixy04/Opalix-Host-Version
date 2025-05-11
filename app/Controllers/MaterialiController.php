<?php
// app/Controllers/MaterialiController.php

class MarcheController {
    public function getMateriali()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                *
            FROM materiali
        ");

        $materiali = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($materiali)) {
            sendJsonResponse(['message' => 'Nessuna categoria trovata']);
        } else {
            sendJsonResponse($materiali);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



}