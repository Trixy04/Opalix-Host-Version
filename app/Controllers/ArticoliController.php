<?php
// app/Controllers/ArticoliController.php

class ArticoliController
{
public function getArticoli($id = null, $codice = null)
{
    global $conn;

    try {
        $sql = "
        SELECT 
            a.id,
            a.codice_articolo,
            a.nome AS nome_articolo,
            a.descrizione,
            a.materiale_id,
            a.categoria_id,
            a.marca_id,
            a.stato_id AS stato_id,
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
        } elseif ($codice !== null) {
            $sql .= " WHERE a.codice_articolo = :codice";
        }

        $stmt = $conn->prepare($sql);

        if ($id !== null) {
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        } elseif ($codice !== null) {
            $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
        }

        $stmt->execute();
        $articoli = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($articoli)) {
            sendJsonResponse(['message' => 'Nessun articolo trovato']);
            exit;  // Interrompe l’esecuzione dopo la risposta
        }

        // Pietre associate per ogni articolo
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

        // Se cercavi per id o codice restituisci solo il primo articolo (oggetto singolo)
        if ($id !== null || $codice !== null) {
            sendJsonResponse($articoli[0]);
        } else {
            // Altrimenti tutta la lista
            sendJsonResponse($articoli);
        }

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        exit;
    }
}

public function getArticoliByCodice(string $codice)
{
    global $conn;

    try {
        $sql = "
        SELECT 
            a.id,
            a.codice_articolo,
            a.nome AS nome_articolo,
            a.descrizione,
            a.materiale_id,
            a.categoria_id,
            a.marca_id,
            a.stato_id AS stato_id,
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
        WHERE a.codice_articolo = :codice
        LIMIT 1
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':codice', $codice, PDO::PARAM_STR);
        $stmt->execute();

        $articolo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$articolo) {
            sendJsonResponse(['message' => 'Nessun articolo trovato'], 404);
            exit;
        }

        // Carica pietre associate
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

        sendJsonResponse($articolo);

    } catch (PDOException $e) {
        sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        exit;
    }
}

    public function getPietreArticolo($id = null)
    {
        global $conn;

        try {
            if ($id === null) {
                sendJsonResponse(['error' => 'ID articolo mancante'], 400);
                return;
            }

            // Query per ottenere le pietre associate a un articolo specifico
            $sql = "
            SELECT 
                ap.id,
                ap.id_pietra AS pietra_id,
                p.nome,
                ap.caratura_pietra AS caratura,
                ap.qta_pietra AS quantita
            FROM articoli_pietre ap
            JOIN pietre p ON ap.id_pietra = p.id
            WHERE ap.id_articolo = :articolo_id
        ";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':articolo_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $pietre = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($pietre)) {
                sendJsonResponse(['message' => 'Nessuna pietra trovata per questo articolo']);
                return;
            }

            sendJsonResponse($pietre);

        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }

    public function addPietraArticolo()
    {
        global $conn;

        // Legge i dati dalla richiesta POST (JSON)
        $data = json_decode(file_get_contents("php://input"), true);

        // Verifica che tutti i dati obbligatori siano presenti
        if (
            empty($data['id_articolo']) ||
            empty($data['id_pietra']) ||
            !isset($data['caratura']) ||
            !isset($data['quantita'])
        ) {
            sendJsonResponse(['error' => 'Dati mancanti per aggiungere la pietra'], 400);
            return;
        }

        try {
            $sql = "INSERT INTO articoli_pietre (id_articolo, id_pietra, caratura_pietra, qta_pietra)
                VALUES (:id_articolo, :id_pietra, :caratura, :quantita)";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_articolo', $data['id_articolo'], PDO::PARAM_INT);
            $stmt->bindParam(':id_pietra', $data['id_pietra'], PDO::PARAM_INT);
            $stmt->bindParam(':caratura', $data['caratura']);
            $stmt->bindParam(':quantita', $data['quantita'], PDO::PARAM_INT);
            $stmt->execute();

            $idInserito = $conn->lastInsertId();
            sendJsonResponse([
                'success' => true,
                'message' => 'Pietra aggiunta con successo',
                'id' => $idInserito
            ], 201);

            sendJsonResponse(['message' => 'Pietra aggiunta con successo'], 201);
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }

    public function updatePietraArticolo($id)
    {
        global $conn;

        // Legge il JSON in ingresso
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            sendJsonResponse(['error' => 'Dati non validi'], 400);
            return;
        }

        // Estrae i campi richiesti
        $id_pietra = $input['id_pietra'] ?? null;
        $caratura = $input['caratura'] ?? null;
        $quantita = $input['quantita'] ?? null;

        // Verifica che tutti i campi siano presenti
        if (!$id_pietra || $caratura === null || $quantita === null) {
            sendJsonResponse(['error' => 'Campi obbligatori mancanti'], 400);
            return;
        }

        try {
            // Prepara la query di aggiornamento
            $sql = "UPDATE articoli_pietre 
                SET id_pietra = :id_pietra, 
                    caratura_pietra = :caratura, 
                    qta_pietra = :quantita 
                WHERE id = :id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':id_pietra', $id_pietra, PDO::PARAM_INT);
            $stmt->bindParam(':caratura', $caratura);
            $stmt->bindParam(':quantita', $quantita, PDO::PARAM_INT);
            $stmt->execute();

            sendJsonResponse(['success' => true, 'message' => 'Pietra modificata con successo']);

        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }


    public function deletePietraArticolo($id = null)
    {
        global $conn;

        try {
            if ($id === null) {
                sendJsonResponse(['error' => 'ID mancante'], 400);
                return;
            }

            $sql = "DELETE FROM articoli_pietre WHERE id = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                if ($stmt->rowCount() > 0) {
                    sendJsonResponse(['message' => 'Pietra rimossa con successo']);
                } else {
                    sendJsonResponse(['message' => 'Nessuna riga trovata da eliminare'], 404);
                }
            } else {
                sendJsonResponse(['error' => 'Errore nell\'eliminazione'], 500);
            }

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
            'codice_articolo',
            'nome_articolo',
            'descrizione',
            'materiale_id',
            'categoria_id',
            'marca_id',
            'peso_materiale',
            'carati_materiale',
            'prezzo_acquisto',
            'prezzo_vendita',
            'quantita',
            'ubicazione',
            'stato_id',
            'note'
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

            $conn->commit();
            sendJsonResponse(['success' => true]);
        } catch (PDOException $e) {
            $conn->rollBack();
            sendJsonResponse(['error' => 'Errore DB: ' . $e->getMessage()], 500);
        }
    }





}