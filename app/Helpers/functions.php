<?php
// helpers/functions.php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function sendJsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit;
}

function generateJWT($userId, $nome, $cognome) {
    $key = "test"; // La tua chiave segreta per JWT
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;  // JWT valido per 1 ora da ora
    $payload = [
        'iat' => $issuedAt,
        'exp' => $expirationTime,
        'sub' => $userId,
        'nome' => $nome,
        'cognome' => $cognome
    ];

    // Ora includiamo anche l'algoritmo di hashing come terzo parametro
    return JWT::encode($payload, $key, 'HS256');  // 'HS256' è l'algoritmo di hashing
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
