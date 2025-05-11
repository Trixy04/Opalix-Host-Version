<?php
// app/Controllers/MarcheController.php

class MarcheController {
    public function getMarche()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                *
            FROM marche
        ");

        $marche = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($marche)) {
            sendJsonResponse(['message' => 'Nessuna categoria trovata']);
        } else {
            sendJsonResponse($marche);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



}