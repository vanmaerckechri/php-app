<?php

namespace App\Controller;

use Core\ {
	AbstractController,
	Router\Router,
	Authentification\Auth,
	Authentification\Oauth,
	MessagesManager\MessagesManager
};

class ConnectionController extends AbstractController
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::CONNEXION',
			'h1' => 'APP-PHP',
			'h2' => 'CONNEXION',
		];
	}

	public function index(): void
	{
		$this->redirect('home', ['logged' => true]);

		$oauthConfig = Oauth::getConfig();
		$this->varPage['goole_id'] = $oauthConfig['goole_id'];
		$this->varPage['google_route'] = $oauthConfig['google_route'];

		$this->varPage['recordedInputs'] = $this->getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		
		$this->renderer('ConnectionView', 'index');
	}

	public function dedicatedConnection(): void
	{
		$this->redirect('home', ['logged' => true]);

		if (!empty($_POST))
		{
			if (!empty($_POST['username']) && !empty($_POST['password']))
			{
				if (Auth::login($_POST['username'], $_POST['password']))
				{
					$this->redirect('home');
					exit();
				}
				else
				{
					$this->recordInputs(['username' => $_POST['username']]);
					MessagesManager::add(['authSms' => ['auth' => null]]);
					$this->redirect('connection');
					exit();	
				}
			}
		}
	}

	public function googleConnection(): void
	{
		$this->redirect('home', ['logged' => true]);

		$oauth = new Oauth();
		if (!$oauth->login('google'))
		{
			$this->redirect('connection');
			exit();
		}
		$this->redirect('home');
		exit();
	}

	public function disconnect(): void
	{
		if (Auth::removeUserFromSession() === true)
		{
			MessagesManager::add(['info' => ['disconnectComplete' => null]]);
		}
		$this->redirect('connection');
		exit();
	}
}