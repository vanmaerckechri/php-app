<?php

require_once('../router/Router.php');

$router = new Router();

$router->get('/', 'ViewManager#loadHomePage', 'home');

$router->get('/test/:id-:slug', 'ViewManager#loadTestPage', 'test')->with('id', '[0-9]+')->with('slug', '([a-z\-0-9]+)');

$router->get('.+', 'ViewManager#load404Page');

$router->run();

var_dump($router->url('home'));
var_dump($router->url('test', ['id' => 45, 'slug' => 'test']));