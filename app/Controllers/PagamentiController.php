<?php
// app/Controllers/PagamentiController.php

class PagamentiController {
    public function getPagamenti()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT id, descrizione
                FROM pagamenti
                ORDER BY id ASC
            ");

            $pagamenti = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($pagamenti)) {
                sendJsonResponse(['message' => 'Nessun pagamento trovato']);
            } else {
                sendJsonResponse($pagamenti);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
