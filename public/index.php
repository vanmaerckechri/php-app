<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Autoloader.php';

use App\App;
use App\Router\Router;


/*
use App\Migration\Migration;
use App\Migration\DbContentGenerator;

$migration = new Migration();
DbContentGenerator::launch([
	'user' => ['iteration' => 5, 'forceRand' => ['created_at']],
	'category' => ['iteration' => 3],
	'article' => ['iteration' => 30, 'forceRand' => ['created_at']]
]);
*/

App::startSession();

$lang = "fr";

$router = new Router($_GET['url']);

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