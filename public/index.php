<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/core/Autoloader.php';

use Core\ {
	App,
	Router\Router
};

App::start();

Router::init();

// Only for development!!! --->

App::devMode();

Router::get('/devboard', 'DevboardController#index', 'devboard');

Router::delete('/devboard', 'DevboardController#delete');

Router::post('/devboard', 'DevboardController#create');

// <--- Only for development!!!

Router::get('/', 'HomeController#index', 'home');

Router::get('/articles/page/:page', 'ArticlesController#index', 'articles')->with('page', '[0-9]+');

Router::get('/article/:id-:slug', 'ArticleController#show', 'article')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

Router::get('/article/new', 'ArticleController#new', 'newArticle');

Router::post('/article/new', 'ArticleController#create');

Router::get('/article/edit/:id-:slug', 'ArticleController#edit', 'editArticle')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

Router::post('/article/edit/:id-:slug', 'ArticleController#update')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

Router::get('/connection', 'ConnectionController#index', 'connection');

Router::post('/connection', 'ConnectionController#dedicatedConnection');

Router::get('/google-connection', 'ConnectionController#googleConnection', 'googleConnection');

Router::get('/disconnect', 'ConnectionController#disconnect', 'disconnect');

Router::get('/registration/:token', 'RegistrationController#validation', 'registrationValidation')->with('token', '([a-z\-0-9]+)');

Router::get('/registration', 'RegistrationController#new', 'registration');

Router::post('/registration', 'RegistrationController#create');

// unknow url

Router::get('.+', 'Error404Controller#show');

Router::get('/404', 'Error404Controller#show', 'error404');

Router::run();