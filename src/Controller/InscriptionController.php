<?php

namespace App\Controller;

use App\App;
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
		$this->varPage['recordedInputs'] = App::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
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
				App::recordInputs(['username' => $_POST['username']]);
				if ($user->checkVarHealth())
				{
					$userRequest = new UserRequest();
					if ($userRequest->record($user))
					{
						MessagesManager::add(['info' => ['registerComplete' => null]]);
						header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
						exit();
					}
					MessagesManager::add(['usernameSms' => ['usernameTaken' => null]]);
				}
			}
		}
		header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('inscription'));
		exit();
	}	
}