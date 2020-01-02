<?php

namespace App\Controller;

use App\Model\User;
use App\Request\UserRequest;
use App\MessagesManager;

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
				$this->varPage['username'] = htmlspecialchars($_POST['username']);
				if ($user->checkVarHealth())
				{
					$userRequest = new UserRequest();
					if ($userRequest->record($user))
					{
						header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
						exit();
					}
					MessagesManager::add(['usernameSms' => ['usernameTaken' => null]]);
				}
			}
		}
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->show();
	}	
}