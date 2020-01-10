<?php

namespace App\Controller;

use App\App;
use App\Model\User;
use App\Repository\UserRepository;
use App\MessagesManager;
use App\Validator\Validator;

Class InscriptionController extends ViewManager
{
	public function __construct()
	{
		$this->redirectLoggedUser('home');

		$this->varPage = [
			'title' => 'APP-PHP::INSCRIPTION',
			'h1' => 'APP-PHP',
			'h2' => 'INSCRIPTION',
			'jsFileNames' => ['confirmPassword']
		];
	}

	public function show()
	{
		$this->varPage['recordedInputs'] = App::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer(['InscriptionView', 'show']);
	}

	public function record()
	{
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
		{
			App::recordInputs(['username' => $_POST['username'], 'email' => $_POST['email']]);

			$user = new User();
			$isValid = true;

			if ($user->isValid(['email' => $_POST['email'], 'username' => $_POST['username']]))
			{
				if (!$user->isUnique(['email', 'username']))
				{
					$isValid = false;
				}				
			}

			if (!$user->isValid(['password' => $_POST['password']]))
			{
				$isValid = false;
			}

			if ($isValid)
			{
				UserRepository::record($user);
				MessagesManager::add(['info' => ['registerComplete' => null]]);
				header('Location: ' . $GLOBALS['router']->url('connexion'));
				exit();
			}
		}

		header('Location: ' . $GLOBALS['router']->url('inscription'));
		exit();
	}	
}
