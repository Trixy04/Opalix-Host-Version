<?php
// app/Controllers/MagazziniController.php

class MagazziniController {
    public function getMagazzini()
    {
        global $conn;

        try {
            $stmt = $conn->query(query: "
                SELECT id, nome
                FROM magazzini
            ");

            $magazzini = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($magazzini)) {
                sendJsonResponse(['message' => 'Nessun magazzino trovato']);
            } else {
                sendJsonResponse($magazzini);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
