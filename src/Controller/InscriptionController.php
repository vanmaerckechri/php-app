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
			$userRequest = new UserRequest();
			$isValid = true;

			App::recordInputs(['username' => $_POST['username'], 'email' => $_POST['email']]);

			// use automatic validation rules with setMultiple
			$user = new User();
			$user->setMultiple([
				'email' => $_POST['email'],
				'username' => $_POST['username'],
				'password' => $_POST['password']
			]);

			// if the email and username entries have been validated, check if it does not already exist in the database
			if (!is_null($user->getEmail()) && $userRequest->findUserByEmail($user->getEmail()))
			{
				$isValid = false;
				MessagesManager::add(['emailSms' => ['emailTaken' => null]]);				
			}
			if (!is_null($user->getUsername()) && $userRequest->findUserByUsername($user->getUsername()))
			{
				$isValid = false;
				MessagesManager::add(['usernameSms' => ['usernameTaken' => null]]);
			}

			// check if the password have been validated
			if (is_null($user->getPassword()))
			{
				$isValid = false;
			}

			// record if all entries are valid
			if ($isValid === true)
			{
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
