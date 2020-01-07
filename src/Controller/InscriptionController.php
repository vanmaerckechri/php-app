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

			// use automatic validation rules with setMultiple
			$user = new User();
			$isValid = $user->isValidToInsert([
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password' => $_POST['password']
			]);

			// record if all values are valid
			if ($isValid)
			{
				$userRequest = new UserRequest();
				$userRequest->record($user);
				MessagesManager::add(['info' => ['registerComplete' => null]]);
				header('Location: ' . $GLOBALS['router']->url('connexion'));
				exit();
			}
		}

		header('Location: ' . $GLOBALS['router']->url('inscription'));
		exit();
	}	
}
