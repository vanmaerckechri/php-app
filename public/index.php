<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Autoloader.php';

use Core\Helper;
use Core\Router\Router;

Helper::startSession();

$lang = "fr";

Router::setUrl($_GET['url']);

// Only for development!!! --->

Helper::getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

Router::get('/devboard', 'DevboardController#index', 'devboard');

Router::delete('/devboard', 'DevboardController#delete');

Router::post('/devboard', 'DevboardController#create');

// <--- Only for development!!!

Router::get('/', 'HomeController#show', 'home');

Router::get('/article/:id-:slug', 'ArticleController#show', 'article')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

Router::get('/connexion', 'ConnexionController#show', 'connexion');

Router::post('/connexion', 'ConnexionController#check', 'connexion');

Router::get('/google-connexion', 'GoogleConnexionController#check', 'googleConnexion');

Router::get('/inscription', 'InscriptionController#show', 'inscription');

Router::post('/inscription', 'InscriptionController#record', 'inscription');

Router::get('/disconnect', 'DisconnectionController#check', 'disconnect');

// unknow url

Router::get('.+', 'Error404Controller#show');

Router::get('/404', 'Error404Controller#show', 'error404');

Router::run();