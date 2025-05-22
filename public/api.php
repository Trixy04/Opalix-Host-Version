<?php 
// public/api.php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../app/Router.php';
require_once '../app/Controllers/CategorieController.php';
require_once '../app/Controllers/AuthController.php';
require_once '../app/Controllers/ClientiController.php';
require_once '../app/Controllers/AziendaController.php';
require_once '../app/Controllers/MarcheController.php';
require_once '../app/Controllers/MaterialiController.php';
require_once '../app/Controllers/ArticoliController.php';
require_once '../app/Controllers/PietreController.php';
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

// Marche
$router->get('/api/marche', 'MarcheController@getMarche');

// Materiali
$router->get('/api/materiali', 'MaterialiController@getMateriali');

// Articoli
$router->get('/api/articoli', 'ArticoliController@getArticoli');
$router->post('/api/articoli', 'ArticoliController@creaArticolo');

// Pietre
$router->get('/api/pietre', 'PietreController@getPietre');

// Dispatch finale
$router->dispatch();
