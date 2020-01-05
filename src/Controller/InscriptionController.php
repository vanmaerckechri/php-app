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
		if (isset($_POST['username']) && isset($_POST['password']))
		{
			App::recordInputs(['username' => $_POST['username'], 'email' => $_POST['email']]);

			$user = App::hydrateModel(new User(), [
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password' => $_POST['password']
			]);
			// if entries are validated...
			if ($user)
			{
				// try to record in db...
				$userRequest = new UserRequest();
				$columnsConflict = $userRequest->record($user);
				if (!$columnsConflict)
				{
					MessagesManager::add(['info' => ['registerComplete' => null]]);
					header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
					exit();
				}
				// email or username already used!
				else
				{
					foreach ($columnsConflict as $column)
					{
						$name = $column . 'Sms';
						$sms = $column . 'Taken';
						MessagesManager::add([$name => [$sms => null]]);
					}
				}
			}
		}

		header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('inscription'));
		exit();
	}	
}