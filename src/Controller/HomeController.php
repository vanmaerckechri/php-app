<?php

namespace App\Controller;

use Core\ {
	AbstractController,
	MessagesManager\MessagesManager
};

use App\Mail\RegistrationMail;

class HomeController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::HOME',
		'h1' => 'APP-PHP',
		'h2' => 'HOME',
		'css' => ['style']
	];

	public function index(): void
	{
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('HomeView', 'index');
	}
}