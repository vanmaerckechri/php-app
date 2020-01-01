<?php

require_once('../src/Autoloader.php');

use App\Router\Router;

$router = new Router();

$router->get('/', 'HomeController#show', 'home');

$router->get('/connexion', 'ConnexionController#show', 'connexion');

$router->post('/connexion', 'ConnexionController#check', 'connexion');

$router->get('/inscription', 'InscriptionController#show', 'inscription');

$router->post('/inscription', 'InscriptionController#record', 'inscription');

$router->get('/test/:id-:slug', 'TestController#show', 'test')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

$router->get('.+', 'Error404Controller#show', 'error404');

$router->run();

/*
var_dump($router->url('home'));
var_dump($router->url('test', ['id' => 45, 'slug' => 'test']));
*/