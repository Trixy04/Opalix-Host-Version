<?php
// routes/web.php

// Includi i controller
require_once '../app/Controllers/GioielliController.php';
require_once '../app/Controllers/CategorieController.php';
require_once '../app/Controllers/ClientiController.php';

// Configura il router
$router = new Router();

// Definisci le rotte per i Gioielli
$router->get('/gioielli', 'GioielliController@getGioielli'); // Ottieni tutti i gioielli
$router->post('/gioielli', 'GioielliController@createGioiello'); // Crea un nuovo gioiello

// Definisci le rotte per le Categorie
$router->get('/categorie', 'CategorieController@getCategorie'); // Ottieni tutte le categorie
$router->post('/categorie', 'CategorieController@createCategoria'); // Crea una nuova categoria

//Rotta per login
$router->post('/login', 'AuthController@login');

//Rotta per clienti
$router->get('/api/clienti', 'ClientiController@getClienti');



// Dispatch delle richieste
$router->dispatch();
