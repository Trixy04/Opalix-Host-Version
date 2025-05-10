<?php 
// public/api.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../app/Router.php';
require_once '../app/Controllers/CategorieController.php';
require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/ClientiController.php';
require_once '../app/Controllers/AziendaController.php';
require_once '../app/Helpers/functions.php';

$router = new Router();

// Autenticazione
$router->post('/api/login', 'AuthController@login');

// Categorie
$router->get('/api/categorie', 'CategorieController@getCategorie');
$router->post('/api/categorie', 'CategorieController@createCategoria');

// Clienti
$router->get('/api/clienti', 'ClientiController@getClienti');
$router->post('/api/clienti', 'ClientiController@creaCliente');
$router->put('/api/clienti/{id}', 'ClientiController@aggiornaCliente');
$router->delete('/api/clienti/{id}', 'ClientiController@eliminaCliente'); // se lo prevedi

// Azienda
$router->get('/api/azienda', 'AziendaController@getAzienda');

// Dispatch finale
$router->dispatch();
