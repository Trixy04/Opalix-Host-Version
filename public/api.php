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
require_once '../app/Controllers/CarichiController.php';
require_once '../app/Controllers/ScarichiController.php';
require_once '../app/Controllers/MagazziniController.php';
require_once '../app/Controllers/DestinazioniController.php';
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

// Destinazioni
$router->get('/api/destinazioni', 'DestinazioniController@getDestinazioni');

// Pagamenti
$router->get('/api/pagamenti', 'PagamentiController@getPagamenti');

// Iva
$router->get('/api/iva', 'IvaController@getIva');

// Documenti
$router->get('/api/doc', 'DocumentiController@getDoc');

// Carichi
$router->get('/api/carichi', 'CarichiController@getCarichi');
$router->get('/api/carichi/{id}', 'CarichiController@getCarichi');
$router->get('/api/carichi/articolo/{id}', 'CarichiController@getCarichiByArticolo');
$router->post('/api/carichi', 'CarichiController@creaCarico');

// Scarichi
$router->get('/api/scarichi', 'ScarichiController@getScarichi');
$router->get('/api/scarichi/{id}', 'ScarichiController@getScarichi');
$router->get('/api/scarichi/articolo/{id}', 'ScarichiController@getScarichiByArticolo');
$router->post('/api/scarichi', 'ScarichiController@creaScarico');

// Documenti carico
$router->get('/api/carichi/allegati/{id}', 'CarichiController@getAllegatiCarico');
$router->post('/api/carichi/upload-allegato', 'CarichiController@uploadAllegatoCarico');

// Magazzini
$router->get('/api/magazzini', 'MagazziniController@getMagazzini');

// Dispatch finale
$router->dispatch();
