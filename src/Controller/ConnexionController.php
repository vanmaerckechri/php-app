<?php

namespace App\Controller;

use Core\ {
	Helper,
	MessagesManager,
	AbstractController,
	Router\Router
};
use App\Authentification\Auth;

class ConnexionController extends AbstractController
{
	public function __construct()
	{
		if (!is_null(Auth::user()))
		{
			header('Location: ' . Router::url('home'));
			exit();
		}

		$this->varPage = [
			'title' => 'APP-PHP::CONNEXION',
			'h1' => 'APP-PHP',
			'h2' => 'CONNEXION',
		];
	}

	public function show(): void
	{
		require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/oauth.php';
		$this->varPage['google_id'] = GOOGLE_ID;
		$this->varPage['recordedInputs'] = Helper::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('ConnexionView', 'show');
	}

	public function check(): void
	{
		if (!empty($_POST))
		{
			if (!empty($_POST['username']) && !empty($_POST['password']))
			{
				if (is_null(Auth::login($_POST['username'], $_POST['password'])))
				{
					Helper::recordInputs(['username' => $_POST['username']]);
					MessagesManager::add(['authSms' => ['auth' => null]]);
					header('Location: ' . Router::url('connexion'));
				}
			}
		}
	}
}