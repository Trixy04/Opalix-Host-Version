<?php
// app/Controllers/ScarichiController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class ScarichiController
{

    public function getScarichi($idScarico = null)
{
    global $conn;

    try {
        // Query base
        $sql = "
        SELECT 
            s.id,
            s.numero_documento,
            s.data_documento,
            s.data_inserimento,
            s.note,
            cl.nome AS cliente_nome,
            cl.cognome AS cliente_cognome,
            cl.codice_fiscale,
            ind.via,
            ind.citta,
            ind.cap,
            ind.provincia,
            ind.paese,
            m.nome AS magazzino,
            td.descrizione AS tipo_documento,
            td.codice AS codice_documento,
            p.descrizione AS pagamento,
            cs.descrizione AS causali_scarico,
            cs.codice AS codice_causale
        FROM scarichi s
        LEFT JOIN clienti cl ON s.id_cliente = cl.id
        LEFT JOIN magazzini m ON s.id_magazzino = m.id
        LEFT JOIN tipologie_documenti td ON s.id_tipo_documento = td.id
        LEFT JOIN pagamenti p ON s.id_pagamento = p.id
        LEFT JOIN causali_scarico cs ON s.id_causale = cs.id
        LEFT JOIN clienti_indirizzi indC ON cl.id = indC.cliente_id
        LEFT JOIN indirizzi ind ON ind.id = indC.indirizzo_id
        ";

        if ($idScarico !== null) {
            $sql .= " WHERE s.id = :id";
        }

        $sql .= " ORDER BY s.data_documento DESC";

        $stmt = $conn->prepare($sql);

        if ($idScarico !== null) {
            $stmt->bindParam(':id', $idScarico, PDO::PARAM_INT);
        }

        $stmt->execute();
        $scarichi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Query per i dettagli articoli
        $stmtDettagli = $conn->prepare("
        SELECT 
            sd.id,
            sd.id_scarico,
            sd.id_articolo,
            a.codice_articolo,
            a.nome AS nome_articolo,
            sd.quantita,
            sd.prezzo_unitario,
            sd.id_iva,
            iva.aliquota,
            sd.note
        FROM scarichi_dettagli sd
        LEFT JOIN articoli a ON sd.id_articolo = a.id
        INNER JOIN aliquote_iva iva ON sd.id_iva = iva.id
        WHERE sd.id_scarico = ?
        ");

        foreach ($scarichi as &$scarico) {
            $stmtDettagli->execute([$scarico['id']]);
            $scarico['articoli'] = $stmtDettagli->fetchAll(PDO::FETCH_ASSOC);
        }

        if ($idScarico !== null) {
            sendJsonResponse($scarichi[0] ?? null, 200);
        } else {
            sendJsonResponse($scarichi, 200);
        }

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



    public function creaScarico()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    // Validazione dei campi obbligatori
    if (
        !isset(
            $data->numero_documento,
            $data->id_tipo_documento,
            $data->data_documento,
            $data->id_magazzino,
            $data->id_utente,
            $data->id_pagamento,
            $data->id_causale,
            $data->totale_scarico,
            $data->articoli
        ) || !is_array($data->articoli) || count($data->articoli) === 0
    ) {
        sendJsonResponse(['error' => 'Dati scarico o articoli mancanti o non validi'], 400);
        return;
    }

    try {
        $conn->beginTransaction();

        // Inserimento dello scarico
        $stmt = $conn->prepare("INSERT INTO scarichi (
            numero_documento, id_tipo_documento, data_documento, data_scarico,
            id_cliente, id_magazzino, id_causale, id_pagamento,
            note, allegato_documento, id_utente, totale_scarico
        ) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([
            $data->numero_documento,
            $data->id_tipo_documento,
            $data->data_documento,
            $data->id_cliente ?? null, // cliente opzionale
            $data->id_magazzino,
            $data->id_causale,
            $data->id_pagamento,
            $data->note ?? null,
            $data->allegato_documento ?? null,
            $data->id_utente,
            $data->totale_scarico
        ]);

        $scarico_id = $conn->lastInsertId();

        // Inserimento dei dettagli articoli
        $stmtArticolo = $conn->prepare("INSERT INTO scarichi_dettagli (
            id_scarico, id_articolo, quantita, prezzo_unitario, id_iva, note
        ) VALUES (?, ?, ?, ?, ?, ?)");

        // Prepariamo anche la query per aggiornare la quantità disponibile
        $stmtAggiornaQuantita = $conn->prepare("UPDATE articoli SET quantita = quantita - ? WHERE id = ?");

        foreach ($data->articoli as $articolo) {
            if (
                !isset($articolo->id_articolo, $articolo->quantita, $articolo->prezzo_unitario, $articolo->id_iva)
            ) {
                throw new Exception("Dati articolo mancanti o incompleti");
            }

            $stmtArticolo->execute([
                $scarico_id,
                $articolo->id_articolo,
                $articolo->quantita,
                $articolo->prezzo_unitario,
                $articolo->id_iva,
                $articolo->note ?? null
            ]);

            // Aggiorna quantità disponibile nell'articolo (sottrae)
            $stmtAggiornaQuantita->execute([
                $articolo->quantita,
                $articolo->id_articolo
            ]);
        }

        $conn->commit();

        sendJsonResponse([
            'message' => 'Scarico inserito con successo',
            'id_scarico' => $scarico_id
        ], 201);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    } catch (Exception $ex) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore: ' . $ex->getMessage()], 400);
    }
}


    public function uploadAllegatoScarico()
{
    global $conn;

    if (!isset($_POST['id_scarico']) || !isset($_FILES['file'])) {
        sendJsonResponse(['error' => 'Parametri mancanti'], 400);
        return;
    }

    $id_scarico = intval($_POST['id_scarico']);
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        sendJsonResponse(['error' => 'Errore nel caricamento del file'], 400);
        return;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newName = 'scarico_' . $id_scarico . '_' . time() . '.' . $ext;

    // ✅ Percorsi
    $relativePath = 'assets/documenti_scarico/' . $newName; // da salvare nel DB
    $uploadDir = dirname(__DIR__, 2) . '/public/assets/documenti_scarico/';
    $destination = $uploadDir . $newName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0775, true);
    }

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        sendJsonResponse(['error' => 'Impossibile salvare il file'], 500);
        return;
    }

    // ✅ Inserisci nella tabella allegati_scarichi
    $stmt = $conn->prepare("INSERT INTO allegati_scarichi (id_scarico, percorso_file, data_caricamento) VALUES (?, ?, NOW())");
    $stmt->execute([$id_scarico, $relativePath]);

    sendJsonResponse(['message' => 'Allegato caricato con successo']);
}


    public function getAllegatiScarico($id)
{
    global $conn; // $conn è un oggetto PDO

    $id_scarico = intval($id);
    if ($id_scarico <= 0) {
        sendJsonResponse(['error' => 'ID scarico non valido'], 400);
        return;
    }

    try {
        $stmt = $conn->prepare("SELECT id, percorso_file, data_caricamento FROM allegati_scarichi WHERE id_scarico = ?");
        $stmt->execute([$id_scarico]);

        $allegati = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $allegati[] = [
                'id' => $row['id'],
                'percorso_file' => $row['percorso_file'],
                'data_caricamento' => $row['data_caricamento'],
                'url' => 'http://localhost/opalix_server/public/' . $row['percorso_file']
            ];
        }

        sendJsonResponse($allegati);

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}


    public function getScarichiByArticolo($idArticolo)
{
    global $conn;

    try {
        // Query per scarichi che includono l'articolo specificato
        $sql = "
        SELECT 
            s.id,
            s.numero_documento,
            s.data_documento,
            s.data_inserimento,
            s.note,
            s.allegato_documento,
            cl.nome AS cliente,
            cl.codice_fiscale,
            indC.indirizzo,
            indC.citta,
            indC.cap,
            indC.provincia,
            indC.nazione,
            m.nome AS magazzino,
            td.descrizione AS tipo_documento,
            td.codice AS codice_documento,
            p.descrizione AS pagamento,
            cs.descrizione AS causali_scarico,
            cs.codice AS codice_causale
        FROM scarichi s
        INNER JOIN scarichi_dettagli sd ON s.id = sd.id_scarico
        LEFT JOIN clienti cl ON s.id_cliente = cl.id
        LEFT JOIN magazzini m ON s.id_magazzino = m.id
        LEFT JOIN tipologie_documenti td ON s.id_tipo_documento = td.id
        LEFT JOIN pagamenti p ON s.id_pagamento = p.id
        LEFT JOIN causali_scarico cs ON s.id_causale = cs.id
        INNER JOIN indirizzi_clienti indC ON cl.id = indC.cliente_id
        WHERE sd.id_articolo = :idArticolo
        GROUP BY s.id
        ORDER BY s.data_documento DESC
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idArticolo', $idArticolo, PDO::PARAM_INT);
        $stmt->execute();
        $scarichi = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Dettagli articoli per ogni scarico
        $stmtDettagli = $conn->prepare("
        SELECT 
            sd.id,
            sd.id_scarico,
            sd.id_articolo,
            a.codice_articolo,
            a.nome AS nome_articolo,
            sd.quantita,
            sd.prezzo_unitario,
            sd.id_iva,
            iva.aliquota,
            sd.note
        FROM scarichi_dettagli sd
        LEFT JOIN articoli a ON sd.id_articolo = a.id
        INNER JOIN aliquote_iva iva ON sd.id_iva = iva.id
        WHERE sd.id_scarico = ?
        ");

        foreach ($scarichi as &$scarico) {
            $stmtDettagli->execute([$scarico['id']]);
            $scarico['articoli'] = $stmtDettagli->fetchAll(PDO::FETCH_ASSOC);
        }

        sendJsonResponse($scarichi, 200);

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

}
