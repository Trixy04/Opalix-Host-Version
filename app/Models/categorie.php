<?php
require_once __DIR__ . '/../../config/config.php';

class Categorie {

    private $pdo;

    public function __construct() {
        $this->pdo = getDbConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM categorie");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO categorie (nome, descrizione) VALUES (?, ?)");
        $stmt->execute([$data['nome'], $data['descrizione'] ?? null]);
    }
}