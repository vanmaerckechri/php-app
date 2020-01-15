<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Autoloader.php';

use Core\Helper;
use Core\Router\Router;

Helper::startSession();

$lang = "fr";

$router = new Router($_GET['url']);

$router->get('/devboard', 'DevboardController#index', 'devboard');

$router->get('/', 'HomeController#show', 'home');

$router->get('/article/:id-:slug', 'ArticleController#show', 'article')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

$router->get('/connexion', 'ConnexionController#show', 'connexion');

$router->post('/connexion', 'ConnexionController#check', 'connexion');

$router->get('/google-connexion', 'GoogleConnexionController#check', 'googleConnexion');

$router->get('/inscription', 'InscriptionController#show', 'inscription');

$router->post('/inscription', 'InscriptionController#record', 'inscription');

$router->get('/disconnect', 'DisconnectionController#check', 'disconnect');

// unknow url

$router->get('.+', 'Error404Controller#show');

$router->get('/404', 'Error404Controller#show', 'error404');

$router->run();