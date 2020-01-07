<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Autoloader.php';

use App\App;
use App\Router\Router;
use App\Migration\Migration;

App::startSession();

$lang = "fr";

$router = new Router($_GET['url']);

$router->get('/google-connexion', 'GoogleConnexionController#check', 'googleConnexion');

$router->get('/connexion', 'ConnexionController#show', 'connexion');

$router->post('/connexion', 'ConnexionController#check', 'connexion');

$router->get('/inscription', 'InscriptionController#show', 'inscription');

$router->post('/inscription', 'InscriptionController#record', 'inscription');

$router->get('/test/:id-:slug', 'TestController#show', 'test')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

$router->get('/disconnect', 'DisconnectionController#check', 'disconnect');

$router->get('/', 'HomeController#show', 'home');

$router->get('.+', 'Error404Controller#show', 'error404');

$router->run();