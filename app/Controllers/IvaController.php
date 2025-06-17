<?php
// app/Controllers/IvaController.php

class IvaController {
    public function getIva()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT id, codice, descrizione
                FROM aliquote_iva
                ORDER BY codice ASC
            ");

            $iva = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($iva)) {
                sendJsonResponse(['message' => 'Nessun aliquota trovata']);
            } else {
                sendJsonResponse($iva);
            }
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }
}
