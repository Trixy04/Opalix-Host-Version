<?php

if (!defined('JWT_SECRET')) {
    define('JWT_SECRET', 'la_tua_chiave_segreta'); // Usa una chiave lunga e sicura
}

$host = 'localhost';
$dbname = 'opalix_db';
$username = 'mattia.teriaca';
$password = 'admin';  // Modifica con la tua password

try {
    // Connessione PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestisci l'errore e restituisci un valore che segnala il fallimento
    die("Connection failed: " . $e->getMessage());
}

// Restituisce la connessione
