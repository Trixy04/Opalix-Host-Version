<?php
// app/Controllers/ArticoliController.php

class ArticoliController {
    public function getArticoli()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                a.id,
                a.codice_articolo,
                a.nome AS nome_articolo,
                a.descrizione,
                c.nome AS categoria,
                m.nome AS marca,
                mat.nome AS materiale,
                a.peso,
                a.carati,
                a.prezzo_acquisto,
                a.prezzo_vendita,
                a.data_acquisto,
                a.quantita,
                a.ubicazione,
                s.nome AS stato,
                a.note,
                a.foto,
                GROUP_CONCAT(p.nome, ap.caratura_pietra SEPARATOR ', ') AS pietre
            FROM articoli a
            LEFT JOIN categorie c ON a.categoria_id = c.id
            LEFT JOIN marche m ON a.marca_id = m.id
            LEFT JOIN materiali mat ON a.materiale_id = mat.id
            LEFT JOIN stati_articolo s ON a.stato_id = s.id
            LEFT JOIN articoli_pietre ap ON a.id = ap.articolo_id
            LEFT JOIN pietre p ON ap.pietra_id = p.id
            GROUP BY a.id;

        ");

        $articoli = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($articoli)) {
            sendJsonResponse(['message' => 'Nessun articolo trovato']);
        } else {
            sendJsonResponse($articoli);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



}