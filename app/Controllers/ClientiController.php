<?php
// app/Controllers/ClientiController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class ClientiController
{
    public function getClienti()
{
    global $conn;

    try {
        $stmt = $conn->query("
            SELECT 
                c.id,
                c.nome,
                c.cognome,
                c.codice_fiscale,
                c.data_nascita,
                c.data_creazione,
                i.via,
                i.citta,
                i.cap,
                i.provincia,
                i.paese,
                ct.cellulare,
                ct.email
            FROM clienti c
            LEFT JOIN clienti_indirizzi ci ON c.id = ci.cliente_id
            LEFT JOIN indirizzi i ON ci.indirizzo_id = i.id
            LEFT JOIN clienti_contatti cc ON c.id = cc.cliente_id
            LEFT JOIN contatti ct ON cc.contatto_id = ct.id
            ORDER BY c.cognome ASC
        ");

        $clienti = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($clienti)) {
            sendJsonResponse(['message' => 'Nessun cliente trovato']);
        } else {
            sendJsonResponse($clienti);
        }
    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

public function creaCliente()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset($data->nome, $data->cognome, $data->codice_fiscale, $data->data_nascita,
                $data->indirizzo->via, $data->indirizzo->cap, $data->indirizzo->citta,
                $data->indirizzo->provincia, $data->indirizzo->paese,
                $data->contatto->cellulare, $data->contatto->email)
    ) {
        sendJsonResponse(['error' => 'Dati incompleti'], 400);
        return;
    }

    try {
        // Controlla se il codice fiscale esiste già
        $stmt = $conn->prepare("SELECT COUNT(*) FROM clienti WHERE codice_fiscale = ?");
        $stmt->execute([$data->codice_fiscale]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            sendJsonResponse(['error' => 'Codice Fiscale già presente'], 400);
            return;
        }

        $conn->beginTransaction();

        // Inserimento cliente
        $stmt = $conn->prepare("INSERT INTO clienti (nome, cognome, codice_fiscale, data_nascita, data_creazione) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute([
            $data->nome,
            $data->cognome,
            $data->codice_fiscale,
            $data->data_nascita
        ]);
        $cliente_id = $conn->lastInsertId();

        // Inserimento indirizzo
        $stmt = $conn->prepare("INSERT INTO indirizzi (via, cap, citta, provincia, paese) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data->indirizzo->via,
            $data->indirizzo->cap,
            $data->indirizzo->citta,
            $data->indirizzo->provincia,
            $data->indirizzo->paese
        ]);
        $indirizzo_id = $conn->lastInsertId();

        // Inserimento contatto
        $stmt = $conn->prepare("INSERT INTO contatti (cellulare, email) VALUES (?, ?)");
        $stmt->execute([
            $data->contatto->cellulare,
            $data->contatto->email
        ]);
        $contatto_id = $conn->lastInsertId();

        // Relazioni
        $stmt = $conn->prepare("INSERT INTO clienti_indirizzi (cliente_id, indirizzo_id) VALUES (?, ?)");
        $stmt->execute([$cliente_id, $indirizzo_id]);

        $stmt = $conn->prepare("INSERT INTO clienti_contatti (cliente_id, contatto_id) VALUES (?, ?)");
        $stmt->execute([$cliente_id, $contatto_id]);

        $conn->commit();

        sendJsonResponse(['message' => 'Cliente inserito con successo'], 201);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

public function aggiornaCliente($id)
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    if (!$data) {
        sendJsonResponse(['error' => 'Nessun JSON ricevuto o JSON malformato'], 400);
        return;
    }

    // Validazione campi principali
    if (
        empty($data->nome) || empty($data->cognome) || empty($data->codice_fiscale) || empty($data->data_nascita) ||
        empty($data->indirizzo->via) || empty($data->indirizzo->cap) || empty($data->indirizzo->citta) ||
        empty($data->indirizzo->provincia) || empty($data->indirizzo->paese) ||
        empty($data->contatto->cellulare) || empty($data->contatto->email)
    ) {
        sendJsonResponse(['error' => 'Dati incompleti o mancanti'], 400);
        return;
    }

    try {
        $conn->beginTransaction();

        // Aggiorna cliente
        $stmt = $conn->prepare("UPDATE clienti SET nome = ?, cognome = ?, codice_fiscale = ?, data_nascita = ? WHERE id = ?");
        $stmt->execute([
            $data->nome,
            $data->cognome,
            $data->codice_fiscale,
            $data->data_nascita,
            $id
        ]);

        // Recupera ID indirizzo
        $stmt = $conn->prepare("SELECT indirizzo_id FROM clienti_indirizzi WHERE cliente_id = ?");
        $stmt->execute([$id]);
        $indirizzo_id = $stmt->fetchColumn();

        if (!$indirizzo_id) {
            throw new Exception("Indirizzo non trovato per il cliente ID $id");
        }

        // Recupera ID contatto
        $stmt = $conn->prepare("SELECT contatto_id FROM clienti_contatti WHERE cliente_id = ?");
        $stmt->execute([$id]);
        $contatto_id = $stmt->fetchColumn();

        if (!$contatto_id) {
            throw new Exception("Contatto non trovato per il cliente ID $id");
        }

        // Aggiorna indirizzo
        $stmt = $conn->prepare("UPDATE indirizzi SET via = ?, cap = ?, citta = ?, provincia = ?, paese = ? WHERE id = ?");
        $stmt->execute([
            $data->indirizzo->via,
            $data->indirizzo->cap,
            $data->indirizzo->citta,
            $data->indirizzo->provincia,
            $data->indirizzo->paese,
            $indirizzo_id
        ]);

        // Aggiorna contatto
        $stmt = $conn->prepare("UPDATE contatti SET cellulare = ?, email = ? WHERE id = ?");
        $stmt->execute([
            $data->contatto->cellulare,
            $data->contatto->email,
            $contatto_id
        ]);

        $conn->commit();
        sendJsonResponse(['message' => 'Cliente aggiornato con successo'], 200);

    } catch (Exception $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore: ' . $e->getMessage()], 500);
    }
}

}
