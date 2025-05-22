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
                a.peso_materiale,
                a.carati_materiale,
                a.prezzo_acquisto,
                a.prezzo_vendita,
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

public function creaArticolo()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    // Campi obbligatori
    if (
        !isset($data->codice_codicearticolo, 
                $data->nome, 
                $data->categoria_id)
    ) {
        sendJsonResponse(['error' => 'Codice articolo, nome e categoria sono obbligatori'], 400);
        return;
    }

    try {
        $conn->beginTransaction();

        $stmt = $conn->prepare("
            INSERT INTO articoli (
                codice_articolo, nome, descrizione, categoria_id, marca_id, materiale_id,
                peso, carati, prezzo_acquisto, prezzo_vendita, data_acquisto,
                quantita, ubicazione, stato_id, note, foto
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

        $stmt->execute([
            $data->codice,
            $data->nome,
            $data->descrizione ?? null,
            $data->categoria_id,
            $data->marca_id ?? null,
            $data->materiale_id ?? null,
            $data->peso ?? null,
            $data->carati ?? null,
            $data->prezzo_acquisto ?? null,
            $data->prezzo_vendita ?? null,
            $data->data_acquisto ?? null,
            $data->quantita ?? null,
            $data->ubicazione ?? null,
            $data->stato_id ?? null,
            $data->note ?? null,
            $data->foto ?? null
        ]);

        $conn->commit();

        sendJsonResponse(['message' => 'Articolo inserito con successo'], 201);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}





}