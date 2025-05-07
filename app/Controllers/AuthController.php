<?php

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../Helpers/functions.php';

use Firebase\JWT\JWT;

class AuthController
{
    public function login()
    {
        // Leggi i dati JSON inviati dal client
        $data = json_decode(file_get_contents('php://input'), true);

        // Controlla se i dati sono presenti
        if (empty($data['email']) || empty($data['password'])) {
            sendJsonResponse(['error' => 'Email e password sono richiesti.'], 400);
            return;
        }

        $email = $data['email'];
        $password = $data['password'];

        // Connessione al DB tramite PDO
        global $conn;

        try {
            // Verifica se l'utente esiste nel database
            $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = :email LIMIT 1");
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                sendJsonResponse(['error' => 'Credenziali non valide.'], 401);
                return;
            }

            // Crea il token JWT
            $token = generateJWT($user['id']);

            // Restituisci il token al client
            sendJsonResponse(['token' => $token]);
        } catch (PDOException $e) {
            sendJsonResponse(['error' => 'Errore nel database: ' . $e->getMessage()], 500);
        }
    }
}

?>
