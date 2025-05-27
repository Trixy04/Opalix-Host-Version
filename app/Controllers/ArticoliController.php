<?php
// app/Controllers/ArticoliController.php

class ArticoliController
{
public function getArticoli($id = null)
{
    global $conn;

    try {
        // Query principale per gli articoli (senza GROUP_CONCAT)
        $sql = "
            SELECT 
                a.id,
                a.codice_articolo,
                a.nome AS nome_articolo,
                a.descrizione,
                a.materiale_id,
                a.categoria_id,
                a.marca_id,
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
                a.foto
            FROM articoli a
            LEFT JOIN categorie c ON a.categoria_id = c.id
            LEFT JOIN marche m ON a.marca_id = m.id
            LEFT JOIN materiali mat ON a.materiale_id = mat.id
            LEFT JOIN stati_articolo s ON a.stato_id = s.id
        ";

        if ($id !== null) {
            $sql .= " WHERE a.id = :id";
        }

        $stmt = $conn->prepare($sql);

        if ($id !== null) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        }

        $stmt->execute();
        $articoli = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($articoli)) {
            sendJsonResponse(['message' => 'Nessun articolo trovato']);
            return;
        }

        // Per ogni articolo, recupera le pietre associate
        foreach ($articoli as &$articolo) {
            $sqlPietre = "
                SELECT 
                    ap.id_pietra AS pietra_id,
                    p.nome,
                    ap.caratura_pietra AS caratura,
                    ap.qta_pietra AS quantita
                FROM articoli_pietre ap
                JOIN pietre p ON ap.id_pietra = p.id
                WHERE ap.id_articolo = :articolo_id
            ";

            $stmtPietre = $conn->prepare($sqlPietre);
            $stmtPietre->bindParam(':articolo_id', $articolo['id'], PDO::PARAM_INT);
            $stmtPietre->execute();
            $articolo['pietre'] = $stmtPietre->fetchAll(PDO::FETCH_ASSOC);
        }

        // Se è una singola richiesta, restituisci il primo oggetto
        sendJsonResponse($id !== null ? $articoli[0] : $articoli);

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}



    public function getArticoloById($id)
    {
        // Richiama la funzione generica passandole l'ID
        $this->getArticoli((int) $id);
    }



    public function creaArticolo()
    {
        global $conn;

        $data = json_decode(file_get_contents("php://input"));
        $percorsoFoto = null;

        if (!empty($data->foto)) {
            if (preg_match('/^data:image\/(\w+);base64,/', $data->foto, $type)) {
                $data->foto = substr($data->foto, strpos($data->foto, ',') + 1);
                $type = strtolower($type[1]);

                $data->foto = base64_decode($data->foto);
                if ($data->foto === false) {
                    sendJsonResponse(['error' => 'Errore nella decodifica dell\'immagine'], 400);
                    return;
                }

                $nomeFile = uniqid('foto_', true) . '.' . $type;
                $percorsoFoto = 'assets/articoli/' . $nomeFile;

                $cartellaAssoluta = __DIR__ . '/../../public/assets/articoli';
                if (!is_dir($cartellaAssoluta)) {
                    mkdir($cartellaAssoluta, 0775, true);
                }

                file_put_contents(__DIR__ . '/../../public/' . $percorsoFoto, $data->foto);
            } else {
                sendJsonResponse(['error' => 'Formato immagine non valido'], 400);
                return;
            }
        } else {

        }

        // Campi obbligatori
        if (
            !isset(
            $data->codice_articolo,
            $data->nome,
            $data->categoria_id
        )
        ) {
            sendJsonResponse(['error' => 'Codice articolo, nome e categoria sono obbligatori'], 400);
            return;
        }

        try {
            $conn->beginTransaction();

            $stmt = $conn->prepare("
            INSERT INTO articoli (
                codice_articolo, nome, descrizione, categoria_id, marca_id, materiale_id,
                peso_materiale, carati_materiale, prezzo_acquisto, prezzo_vendita,
                quantita, ubicazione, stato_id, note, foto
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )
        ");

            $stmt->execute([
                $data->codice_articolo,
                $data->nome,
                $data->descrizione ?? null,
                $data->categoria_id,
                ($data->marca_id !== "null" && $data->marca_id !== "") ? $data->marca_id : null,
                ($data->materiale_id !== "null" && $data->materiale_id !== "") ? $data->materiale_id : null,
                $data->peso !== "null" ? $data->peso : null,
                $data->carati !== "null" ? $data->carati : null,
                $data->prezzo_acquisto !== "null" ? $data->prezzo_acquisto : null,
                $data->prezzo_vendita !== "null" ? $data->prezzo_vendita : null,
                $data->quantita !== "null" ? $data->quantita : null,
                $data->ubicazione ?? null,
                $data->stato_id,
                null, // note
                $percorsoFoto, // 👈 QUI USI IL PERCORSO RELATIVO SALVATO
            ]);

            $id_articolo = $conn->lastInsertId();

            if (!empty($data->pietre)) {
                $stmtPietre = $conn->prepare("
        INSERT INTO articoli_pietre (
            id_articolo, id_pietra, caratura_pietra, qta_pietra
        ) VALUES (?, ?, ?, ?)
    ");

                foreach ($data->pietre as $pietra) {
                    // Salta se un valore essenziale è mancante
                    if (empty($pietra->id) || empty($pietra->qta) || empty($pietra->carati))
                        continue;

                    $stmtPietre->execute([
                        $id_articolo,
                        $pietra->id,
                        $pietra->carati,
                        $pietra->qta
                    ]);
                }
            }


            $conn->commit();

            sendJsonResponse(['message' => 'Articolo inserito con successo'], 201);

        } catch (PDOException $e) {
            $conn->rollBack();
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }

public function updateArticolo($id)
{
    global $conn;

    // Ricevi input JSON
    $input = json_decode(file_get_contents("php://input"), true);

    if (!$input) {
        sendJsonResponse(['error' => 'Dati JSON non validi o assenti'], 400);
        return;
    }

    // Verifica che tutti i campi richiesti siano presenti
    $requiredFields = [
        'codice_articolo', 'nome_articolo', 'descrizione', 'materiale_id', 'categoria_id',
        'marca_id', 'peso_materiale', 'carati_materiale', 'prezzo_acquisto', 'prezzo_vendita',
        'quantita', 'ubicazione', 'stato_id', 'note'
    ];

    foreach ($requiredFields as $field) {
        if (!isset($input[$field])) {
            sendJsonResponse(['error' => "Campo mancante: $field"], 400);
            return;
        }
    }

    try {
        $conn->beginTransaction();

        // Aggiorna tabella articoli
        $stmt = $conn->prepare("
            UPDATE articoli SET
                codice_articolo = :codice_articolo,
                nome = :nome_articolo,
                descrizione = :descrizione,
                materiale_id = :materiale_id,
                categoria_id = :categoria_id,
                marca_id = :marca_id,
                peso_materiale = :peso_materiale,
                carati_materiale = :carati_materiale,
                prezzo_acquisto = :prezzo_acquisto,
                prezzo_vendita = :prezzo_vendita,
                quantita = :quantita,
                ubicazione = :ubicazione,
                stato_id = :stato_id,
                note = :note
            WHERE id = :id
        ");

        $stmt->execute([
            ':codice_articolo' => $input['codice_articolo'],
            ':nome_articolo' => $input['nome_articolo'],
            ':descrizione' => $input['descrizione'],
            ':materiale_id' => $input['materiale_id'],
            ':categoria_id' => $input['categoria_id'],
            ':marca_id' => $input['marca_id'],
            ':peso_materiale' => $input['peso_materiale'],
            ':carati_materiale' => $input['carati_materiale'],
            ':prezzo_acquisto' => $input['prezzo_acquisto'],
            ':prezzo_vendita' => $input['prezzo_vendita'],
            ':quantita' => $input['quantita'],
            ':ubicazione' => $input['ubicazione'],
            ':stato_id' => $input['stato_id'],
            ':note' => $input['note'],
            ':id' => $id
        ]);

        // Verifica se l'articolo esiste
        if ($stmt->rowCount() === 0) {
            $conn->rollBack();
            sendJsonResponse(['error' => 'Articolo non trovato o nessuna modifica effettuata'], 404);
            return;
        }

        // Elimina pietre esistenti
        $conn->prepare("DELETE FROM articoli_pietre WHERE id_articolo = :id")->execute([':id' => $id]);

        // Inserisci nuove pietre (massimo 4)
        if (!empty($input['pietre']) && is_array($input['pietre'])) {
            $stmtPietra = $conn->prepare("
                INSERT INTO articoli_pietre (id_articolo, id_pietra, caratura_pietra, qta_pietra)
                VALUES (:id_articolo, :id_pietra, :caratura_pietra, :qta_pietra)
            ");

            $pietre = array_slice($input['pietre'], 0, 4); // massimo 4 pietre

            foreach ($pietre as $pietra) {
                if (
                    !empty($pietra['pietra_id']) &&
                    isset($pietra['caratura'], $pietra['quantita']) &&
                    is_numeric($pietra['caratura']) &&
                    is_numeric($pietra['quantita'])
                ) {
                    $stmtPietra->execute([
                        ':id_articolo' => $id,
                        ':id_pietra' => $pietra['pietra_id'],
                        ':caratura_pietra' => $pietra['caratura'],
                        ':qta_pietra' => $pietra['quantita']
                    ]);
                }
            }
        }

        $conn->commit();
        sendJsonResponse(['success' => true]);
    } catch (PDOException $e) {
        $conn->rollBack();
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
    }
}





}