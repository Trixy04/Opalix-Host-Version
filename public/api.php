<?php
// public/api.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../app/Router.php';
require_once '../app/Controllers/CategorieController.php';
require_once '../app/Helpers/functions.php';
require_once '../app/Controllers/AuthController.php';


// Crea un'istanza del router
$router = new Router();

// Definisci le rotte per le API
$router->post('/api/login', 'AuthController@login');

$router->get('/api/categorie', 'CategorieController@getCategorie');
$router->post('/api/categorie', 'CategorieController@createCategoria');




// Esegui il dispatch della richiesta
$router->dispatch();

