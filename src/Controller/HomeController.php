<?php

namespace App\Controller;

use Core\ {
	AbstractController,
	MessagesManager\MessagesManager
};

class HomeController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::HOME',
		'h1' => 'APP-PHP',
		'h2' => 'HOME',
	];

	public function index()
	{
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('HomeView', 'index');
	}	
}