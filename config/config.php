<?php
// config/config.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'opalix_db');
define('DB_USER', 'mattia.teriaca'); // modifica se usi un altro utente
define('DB_PASS', 'admin'); // inserisci la tua password MySQL, se ce n'è una

function getDbConnection() {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME,
            DB_USER,
            DB_PASS
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
}
