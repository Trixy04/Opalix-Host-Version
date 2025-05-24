<?php
// app/Controllers/ArticoliController.php

class ArticoliController
{
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
        GROUP_CONCAT(CONCAT(p.nome, ' ', ap.caratura_pietra, ' ct (x', ap.qta_pietra, ')') SEPARATOR ', ') AS pietre
    FROM articoli a
    LEFT JOIN categorie c ON a.categoria_id = c.id
    LEFT JOIN marche m ON a.marca_id = m.id
    LEFT JOIN materiali mat ON a.materiale_id = mat.id
    LEFT JOIN stati_articolo s ON a.stato_id = s.id
    LEFT JOIN articoli_pietre ap ON a.id = ap.id_articolo
    LEFT JOIN pietre p ON ap.id_pietra = p.id
    GROUP BY a.id
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
        }else{
            
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





}