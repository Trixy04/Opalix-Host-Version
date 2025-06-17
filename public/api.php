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
require_once '../app/Controllers/FornitoriController.php';
require_once '../app/Controllers/CausaliController.php';
require_once '../app/Controllers/PagamentiController.php';
require_once '../app/Controllers/IvaController.php';
require_once '../app/Controllers/DocumentiController.php';
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
$router->get('/api/articoli', 'ArticoliController@getArticoli'); // Tutti gli articoli
$router->get('/api/articoli/{id}', 'ArticoliController@getArticoloById'); // Articolo singolo
$router->get('/api/articoli-codice/{codice}', 'ArticoliController@getArticoliByCodice'); // Articolo singolo
$router->get('/api/pietre-articoli/{id}', 'ArticoliController@getPietreArticolo'); // Pietre articolo
$router->post('/api/pietre-articoli', 'ArticoliController@addPietraArticolo'); // Pietre articolo
$router->post('/api/articoli', 'ArticoliController@creaArticolo');
$router->put('/api/articoli/{id}', 'ArticoliController@updateArticolo');
$router->put('/api/pietre-articoli/{id}', 'ArticoliController@updatePietraArticolo'); // Pietre articolo
$router->delete('/api/pietre-articoli/{id}', 'ArticoliController@deletePietraArticolo');

// Pietre
$router->get('/api/pietre', 'PietreController@getPietre');

// Fornitori
$router->get('/api/fornitori', 'FornitoriController@getFornitori');
$router->post('/api/fornitori', 'FornitoriController@creaFornitore');
$router->put('/api/fornitori', 'FornitoriController@modificaFornitore');

// Causali
$router->get('/api/causali', 'CausaliController@getCausali');

// Pagamenti
$router->get('/api/pagamenti', 'PagamentiController@getPagamenti');

// Iva
$router->get('/api/iva', 'IvaController@getIva');

// Documenti
$router->get('/api/doc', 'DocumentiController@getDoc');

// Dispatch finale
$router->dispatch();
