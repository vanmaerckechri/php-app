<?php

namespace App\Controller;

use App\Model\User;
use App\ErrorsManager;

Class InscriptionController extends ViewManager
{
	public function __construct()
	{
		$this->redirectLoggedUser('home');

		$this->varPage = [
			'title' => 'INSCRIPTION',
			'h1' => 'INSCRIPTION',
			'jsFileNames' => ['confirmPassword']
		];
	}

	public function show()
	{
		$this->loadPage(['InscriptionView', 'show'], $this->varPage);
	}

	public function record()
	{
		if (!empty($_POST))
		{
			if (isset($_POST['username']) && isset($_POST['password']))
			{
				$user = new User();
				$user->setUsername($_POST['username']);
				$user->setPassword($_POST['password']);

				$this->varPage['errors'] = ErrorsManager::getMessages();
			}
		}
		$this->show();
	}	
}