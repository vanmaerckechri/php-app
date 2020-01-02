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
				$username = $_POST['username'];
				$password = $_POST['password'];

				if (is_null(Auth::login($username, $password)))
				{
					$this->varPage['username'] = htmlspecialchars($username);
					$this->varPage['errors'] = ErrorsManager::getMessage(['auth'], $GLOBALS['lang']);
				}
			}
		}
		$this->show();
	}
}