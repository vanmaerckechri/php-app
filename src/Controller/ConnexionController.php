<?php

namespace App\Controller;

use App\App;
use App\Authentification\Auth;
use App\MessagesManager;

Class ConnexionController extends ViewManager
{
	public function __construct()
	{
		$this->redirectLoggedUser('home');

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
		$this->varPage['recordedInputs'] = App::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer(['ConnexionView', 'show']);
	}

	public function check(): void
	{
		if (!empty($_POST))
		{
			if (!empty($_POST['username']) && !empty($_POST['password']))
			{
				if (is_null(Auth::login($_POST['username'], $_POST['password'])))
				{
					App::recordInputs(['username' => $_POST['username']]);
					MessagesManager::add(['authSms' => ['auth' => null]]);
					header('Location: ' . $GLOBALS['router']->url('connexion'));
				}
			}
		}
	}
}