<?php
// helpers/functions.php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function generateJWT($userId) {
    $payload = [
        'userId' => $userId,
        'exp' => time() + 3600
    ];

    return JWT::encode($payload, JWT_SECRET, 'HS256');
}

function verifyJWT($token) {
    // Usa la chiave segreta dalla configurazione
    $secretKey = JWT_SECRET;

    try {
        return (array) JWT::decode($token, new Key($secretKey, 'HS256'));
    } catch (Exception $e) {
        return null;  // Se il token non è valido
    }
}
