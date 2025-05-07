<?php
// app/Controllers/CategorieController.php

require_once __DIR__ . '/../Models/Categorie.php';

class CategorieController {
    public function getCategorie() {
        $model = new Categorie();
        $categorie = $model->getAll();
        sendJsonResponse($categorie);
    }

    public function createCategoria() {
        $data = json_decode(file_get_contents('php://input'), true);
        $model = new Categorie();
        $model->create($data);
        sendJsonResponse(["message" => "Categoria creata con successo"]);
    }
}
