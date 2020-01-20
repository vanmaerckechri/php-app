<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Autoloader.php';

use Core\Helper;
use Core\Router\Router;

Helper::startSession();

$lang = "fr";

Router::init();

// Only for development!!! --->

Helper::devMode();

Router::get('/devboard', 'DevboardController#index', 'devboard');

Router::delete('/devboard', 'DevboardController#delete');

Router::post('/devboard', 'DevboardController#create');

// <--- Only for development!!!

Router::get('/', 'HomeController#index', 'home');

Router::get('/article/:id-:slug', 'ArticleController#show', 'article')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

Router::get('/connection', 'ConnectionController#index', 'connection');

Router::post('/connection', 'ConnectionController#dedicatedConnection');

Router::get('/google-connection', 'ConnectionController#googleConnection', 'googleConnection');

Router::get('/disconnect', 'ConnectionController#disconnect', 'disconnect');

Router::get('/registration', 'RegistrationController#new', 'registration');

Router::post('/registration', 'RegistrationController#create');

// unknow url

Router::get('.+', 'Error404Controller#show');

Router::get('/404', 'Error404Controller#show', 'error404');

Router::run();