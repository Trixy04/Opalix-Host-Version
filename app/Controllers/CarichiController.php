<?php
// app/Controllers/CarichiController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class CarichiController
{

    public function getCarichi()
{
    global $conn;

    try {
        // Query principale per i carichi con join per i campi descrittivi
        $stmt = $conn->prepare("
            SELECT 
                c.id,
                c.numero_documento,
                c.data_documento,
                c.data_inserimento,
                c.totale_carico,
                c.note,
                c.allegato_documento,
                
                f.ragione_sociale AS fornitore,
                m.nome AS magazzino,
                td.descrizione AS tipo_documento,
                td.codice AS codice_documento,
                p.descrizione AS pagamento,
                ca.descrizione AS causali_carico,
                ca.codice AS codice_causale

            FROM carichi c
            LEFT JOIN fornitori f ON c.id_fornitore = f.id
            LEFT JOIN magazzini m ON c.id_magazzino = m.id
            LEFT JOIN tipologie_documenti td ON c.id_tipo_documento = td.id
            LEFT JOIN pagamenti p ON c.id_pagamento = p.id
            LEFT JOIN causali_carico ca ON c.id_causale = ca.id
            ORDER BY c.data_documento DESC
        ");
        $stmt->execute();
        $carichi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Recupera i dettagli degli articoli per ogni carico
        $stmtDettagli = $conn->prepare("
            SELECT 
                cd.id,
                cd.id_carico,
                cd.id_articolo,
                a.nome AS nome_articolo,
                cd.quantita,
                cd.prezzo_unitario,
                cd.id_iva,
                cd.note
            FROM carichi_dettagli cd
            LEFT JOIN articoli a ON cd.id_articolo = a.id
            WHERE cd.id_carico = ?
        ");

        // Per ogni carico, aggiungi gli articoli
        foreach ($carichi as &$carico) {
            $stmtDettagli->execute([$carico['id']]);
            $carico['articoli'] = $stmtDettagli->fetchAll(PDO::FETCH_ASSOC);
        }

        sendJsonResponse($carichi, 200);

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

public function creaCarico() 
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    // Validazione dei campi obbligatori
    if (
        !isset(
            $data->numero_documento,
            $data->id_tipo_documento,
            $data->data_documento,
            $data->id_fornitore,
            $data->id_magazzino,
            $data->id_utente,
            $data->id_pagamento,
            $data->id_causale,
            $data->totale_carico,
            $data->articoli
        ) || !is_array($data->articoli) || count($data->articoli) === 0
    ) {
        sendJsonResponse(['error' => 'Dati carico o articoli mancanti o non validi'], 400);
        return;
    }

    try {
        $conn->beginTransaction();

        // Inserimento del carico
        $stmt = $conn->prepare("INSERT INTO carichi (
            numero_documento, id_tipo_documento, data_documento, data_carico, 
            id_fornitore, id_magazzino, id_causale, id_pagamento, 
            note, allegato_documento, id_utente, totale_carico
        ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data->numero_documento,
            $data->id_tipo_documento,
            $data->data_documento,
            $data->id_fornitore,
            $data->id_magazzino,
            $data->id_causale,
            $data->id_pagamento,
            $data->note ?? null,
            $data->allegato_documento ?? null,
            $data->id_utente,
            $data->totale_carico
        ]);

        $carico_id = $conn->lastInsertId();

        // Inserimento dei dettagli articoli
        $stmtArticolo = $conn->prepare("INSERT INTO carichi_dettagli (
            id_carico, id_articolo, quantita, prezzo_unitario, id_iva, note
        ) VALUES (?, ?, ?, ?, ?, ?)");

        // Prepariamo anche la query per aggiornare la quantità disponibile
        $stmtAggiornaQuantita = $conn->prepare("UPDATE articoli SET quantita = quantita + ? WHERE id = ?");

        foreach ($data->articoli as $articolo) {
            if (
                !isset($articolo->id_articolo, $articolo->quantita, $articolo->prezzo_unitario, $articolo->id_iva)
            ) {
                throw new Exception("Dati articolo mancanti o incompleti");
            }

            $stmtArticolo->execute([
                $carico_id,
                $articolo->id_articolo,
                $articolo->quantita,
                $articolo->prezzo_unitario,
                $articolo->id_iva,
                $articolo->note ?? null
            ]);

            // Aggiorna quantità disponibile nell'articolo
            $stmtAggiornaQuantita->execute([
                $articolo->quantita,
                $articolo->id_articolo
            ]);
        }

        $conn->commit();

        sendJsonResponse([
            'message' => 'Carico inserito con successo',
            'id_carico' => $carico_id
        ], 201);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    } catch (Exception $ex) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore: ' . $ex->getMessage()], 400);
    }
}





}
