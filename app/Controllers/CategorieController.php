<?php
// app/Controllers/CategorieController.php

class CategorieController {
    public function getCategorie()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                *
            FROM categorie
        ");

        $categorie = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($categorie)) {
            sendJsonResponse(['message' => 'Nessuna categoria trovata']);
        } else {
            sendJsonResponse($categorie);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



}
