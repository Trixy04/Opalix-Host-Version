<?php
// routes/web.php

require_once '../app/Controllers/GioielliController.php';
require_once '../app/Models/Gioielli.php';

$router = new Router();

$router->get('/gioielli', 'GioielliController@getGioielli');
$router->post('/gioielli', 'GioielliController@createGioiello');