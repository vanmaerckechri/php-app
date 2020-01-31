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
	];

	public function index()
	{
		$token = md5(microtime(TRUE)*100000);
		//RegistrationMail::send('christophe.vm@skynet.be', ['token' => $token]);

		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('HomeView', 'index');
	}
}