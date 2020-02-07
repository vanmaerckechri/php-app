<?php

namespace App\Controller;

use Core\ {
	Router\Router,
	AbstractController,
	Authentification\Auth,
	Authentification\Oauth,
	MessagesManager\MessagesManager
};

use App\Mail\RegistrationMail;

class ConnectionController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::CONNEXION',
		'h1' => 'APP-PHP',
		'h2' => 'CONNEXION',
	];

	public function index(): void
	{
		$this->redirect('home', ['logged' => true]);

		$oauthGoogle = Oauth::getConfig('google');
		$this->varPage['goole_id'] = $oauthGoogle['goole_id'];
		$this->varPage['google_route'] = $oauthGoogle['google_route'];

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
					$user = Auth::user();
					$status = $user->getStatus();
					// status 1 => need activation by email, status 0 => account disable
					if ($status < 2)
					{
						$email = $user->getEmail();
						$token = $user->getToken();

						RegistrationMail::send($email, ['token' => $token]);
						Auth::removeUserFromSession();
						MessagesManager::add(['info' => ['accountNotActivated' => null]]);
						$this->redirect('connection');
					}
					$this->redirect('home');
				}
				else
				{
					$this->recordInputs(['username' => $_POST['username']]);
					MessagesManager::add(['authSms' => ['auth' => null]]);
					$this->redirect('connection');
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
		}
		$this->redirect('home');
	}

	public function disconnect(): void
	{
		if (Auth::removeUserFromSession() === true)
		{
			MessagesManager::add(['info' => ['disconnectComplete' => null]]);
		}
		$this->redirect('connection');
	}
}