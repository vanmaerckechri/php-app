<?php

namespace App\Controller;

use App\App;
use App\Auth;
use App\MessagesManager;

Class ConnexionController extends ViewManager
{
	public function __construct()
	{
		$this->redirectLoggedUser('home');

		$this->varPage = [
			'title' => 'CONNEXION',
			'h1' => 'CONNEXION',
		];
	}

	public function show(): void
	{
		$this->varPage['recordedInputs'] = App::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->loadPage(['ConnexionView', 'show'], $this->varPage);
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
					header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
				}
			}
		}
	}
}