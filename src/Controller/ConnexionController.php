<?php

namespace App\Controller;

use App\Auth;
use App\ErrorsManager;

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
					$this->varPage['username'] = htmlspecialchars($_POST['username']);
					$this->varPage['errors'] = ErrorsManager::add(['authSms' => ['auth' => null]]);
					$this->varPage['errors'] = ErrorsManager::getMessages();
				}
			}
		}
		$this->show();
	}
}