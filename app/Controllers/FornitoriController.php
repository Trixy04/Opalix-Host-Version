<?php
// app/Controllers/FornitoriController.php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

class FornitoriController
{
    public function getFornitori()
    {
        global $conn;

        try {
            $stmt = $conn->query("
                SELECT 
                    f.id,
                    f.codice_fornitore,
                    f.ragione_sociale,
                    f.partita_iva,
                    f.codice_fiscale,
                    f.stato,
                    f.data_creazione,
                    i.tipo AS tipo_indirizzo,
                    i.indirizzo,
                    i.cap,
                    i.citta,
                    i.provincia,
                    i.nazione,
                    c.tipo AS tipo_contatto,
                    c.valore AS contatto_valore,
                    c.descrizione AS contatto_descrizione
                FROM fornitori f
                LEFT JOIN indirizzi_fornitori i ON f.id = i.fornitore_id
                LEFT JOIN contatti_fornitori c ON f.id = c.fornitore_id
                ORDER BY f.ragione_sociale ASC
            ");

            $righe = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($righe)) {
                sendJsonResponse(['message' => 'Nessun fornitore trovato']);
                return;
            }

            // Raggruppamento per fornitore
            $fornitori = [];

            foreach ($righe as $riga) {
                $id = $riga['id'];

                if (!isset($fornitori[$id])) {
                    $fornitori[$id] = [
                        'id' => $riga['id'],
                        'codice_fornitore' => $riga['codice_fornitore'],
                        'ragione_sociale' => $riga['ragione_sociale'],
                        'partita_iva' => $riga['partita_iva'],
                        'codice_fiscale' => $riga['codice_fiscale'],
                        'stato' => $riga['stato'],
                        'data_creazione' => $riga['data_creazione'],
                        'indirizzi' => [],
                        'contatti' => [],
                    ];
                }

                // Aggiungi indirizzo se presente
                if (!empty($riga['indirizzo'])) {
                    $fornitori[$id]['indirizzi'][] = [
                        'tipo' => $riga['tipo_indirizzo'],
                        'indirizzo' => $riga['indirizzo'],
                        'cap' => $riga['cap'],
                        'citta' => $riga['citta'],
                        'provincia' => $riga['provincia'],
                        'nazione' => $riga['nazione']
                    ];
                }

                // Aggiungi contatto se presente
                if (!empty($riga['contatto_valore'])) {
                    $fornitori[$id]['contatti'][] = [
                        'tipo' => $riga['tipo_contatto'],
                        'valore' => $riga['contatto_valore'],
                        'descrizione' => $riga['contatto_descrizione']
                    ];
                }
            }

            // Reindicizza per ottenere un array semplice
            sendJsonResponse(array_values($fornitori));

        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }

public function creaFornitore()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset(
            $data->ragione_sociale,
            $data->partita_iva,
            $data->codice_fiscale,
            $data->indirizzo->tipo,
            $data->indirizzo->indirizzo,
            $data->indirizzo->cap,
            $data->indirizzo->citta,
            $data->indirizzo->provincia,
            $data->indirizzo->nazione,
            $data->contatti
        ) || !is_array($data->contatti)
    ) {
        sendJsonResponse(['error' => 'Dati incompleti o contatti non validi'], 400);
        return;
    }

    try {
        // Controlla se la partita IVA esiste già
        $stmt = $conn->prepare("SELECT COUNT(*) FROM fornitori WHERE partita_iva = ?");
        $stmt->execute([$data->partita_iva]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            sendJsonResponse(['error' => 'Partita IVA già presente'], 400);
            return;
        }

        $conn->beginTransaction();

        // Calcola codice_fornitore progressivo
        $stmt = $conn->query("SELECT codice_fornitore FROM fornitori ORDER BY codice_fornitore DESC LIMIT 1");
        $ultimoCodice = $stmt->fetchColumn();

        if ($ultimoCodice && preg_match('/F(\d+)/', $ultimoCodice, $matches)) {
            $numero = (int)$matches[1];
            $nuovoCodice = 'F' . str_pad($numero + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nuovoCodice = 'F001';
        }

        // Inserimento fornitore
        $stmt = $conn->prepare("INSERT INTO fornitori (codice_fornitore, ragione_sociale, partita_iva, codice_fiscale, data_creazione, stato) VALUES (?, ?, ?, ?, NOW(), 'attivo')");
        $stmt->execute([
            $nuovoCodice,
            $data->ragione_sociale,
            $data->partita_iva,
            $data->codice_fiscale
        ]);
        $fornitore_id = $conn->lastInsertId();

        // Inserimento indirizzo
        $stmt = $conn->prepare("INSERT INTO indirizzi_fornitori (fornitore_id, tipo, indirizzo, cap, citta, provincia, nazione) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $fornitore_id,
            $data->indirizzo->tipo,
            $data->indirizzo->indirizzo,
            $data->indirizzo->cap,
            $data->indirizzo->citta,
            $data->indirizzo->provincia,
            $data->indirizzo->nazione
        ]);

        // Inserimento contatti
        $stmt = $conn->prepare("INSERT INTO contatti_fornitori (fornitore_id, tipo, valore, descrizione) VALUES (?, ?, ?, ?)");
        foreach ($data->contatti as $contatto) {
            if (!isset($contatto->tipo, $contatto->valore)) {
                continue;
            }

            $stmt->execute([
                $fornitore_id,
                $contatto->tipo,
                $contatto->valore,
                $contatto->descrizione ?? null
            ]);
        }

        $conn->commit();

        sendJsonResponse(['message' => 'Fornitore inserito con successo', 'codice_fornitore' => $nuovoCodice], 201);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}

public function modificaFornitore()
{
    global $conn;

    $data = json_decode(file_get_contents("php://input"));

    if (
        !isset(
            $data->id,
            $data->ragione_sociale,
            $data->partita_iva,
            $data->codice_fiscale,
            $data->indirizzo->tipo,
            $data->indirizzo->indirizzo,
            $data->indirizzo->cap,
            $data->indirizzo->citta,
            $data->indirizzo->provincia,
            $data->indirizzo->nazione,
            $data->contatti
        ) || !is_array($data->contatti)
    ) {
        sendJsonResponse(['error' => 'Dati incompleti o contatti non validi'], 400);
        return;
    }

    try {
        $conn->beginTransaction();

        // Verifica se esiste un altro fornitore con la stessa partita IVA
        $stmt = $conn->prepare("SELECT COUNT(*) FROM fornitori WHERE partita_iva = ? AND id != ?");
        $stmt->execute([$data->partita_iva, $data->id]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            sendJsonResponse(['error' => 'Partita IVA già presente per un altro fornitore'], 400);
            return;
        }

        // Aggiorna fornitore
        $stmt = $conn->prepare("UPDATE fornitori SET ragione_sociale = ?, partita_iva = ?, codice_fiscale = ? WHERE id = ?");
        $stmt->execute([
            $data->ragione_sociale,
            $data->partita_iva,
            $data->codice_fiscale,
            $data->id
        ]);

        // Aggiorna indirizzo (se già esiste lo aggiorna, altrimenti lo crea)
        $stmt = $conn->prepare("SELECT id FROM indirizzi_fornitori WHERE fornitore_id = ?");
        $stmt->execute([$data->id]);
        $indirizzo_id = $stmt->fetchColumn();

        if ($indirizzo_id) {
            $stmt = $conn->prepare("UPDATE indirizzi_fornitori SET tipo = ?, indirizzo = ?, cap = ?, citta = ?, provincia = ?, nazione = ? WHERE fornitore_id = ?");
            $stmt->execute([
                $data->indirizzo->tipo,
                $data->indirizzo->indirizzo,
                $data->indirizzo->cap,
                $data->indirizzo->citta,
                $data->indirizzo->provincia,
                $data->indirizzo->nazione,
                $data->id
            ]);
        } else {
            $stmt = $conn->prepare("INSERT INTO indirizzi_fornitori (fornitore_id, tipo, indirizzo, cap, citta, provincia, nazione) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data->id,
                $data->indirizzo->tipo,
                $data->indirizzo->indirizzo,
                $data->indirizzo->cap,
                $data->indirizzo->citta,
                $data->indirizzo->provincia,
                $data->indirizzo->nazione
            ]);
        }

        // Elimina contatti precedenti
        $stmt = $conn->prepare("DELETE FROM contatti_fornitori WHERE fornitore_id = ?");
        $stmt->execute([$data->id]);

        // Inserisce i nuovi contatti
        $stmt = $conn->prepare("INSERT INTO contatti_fornitori (fornitore_id, tipo, valore, descrizione) VALUES (?, ?, ?, ?)");
        foreach ($data->contatti as $contatto) {
            if (!isset($contatto->tipo, $contatto->valore)) {
                continue;
            }

            $stmt->execute([
                $data->id,
                $contatto->tipo,
                $contatto->valore,
                $contatto->descrizione ?? null
            ]);
        }

        $conn->commit();

        sendJsonResponse(['message' => 'Fornitore aggiornato con successo'], 200);

    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}




}
