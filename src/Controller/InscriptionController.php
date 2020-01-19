<?php

namespace App\Controller;

use Core\ {
	Helper,
	Validator,
	AbstractController,
	Router\Router,
	MessagesManager\MessagesManager
};
use App\Model\User;
use App\Repository\UserRepository;
use App\Authentification\Auth;

class InscriptionController extends AbstractController
{
	public function __construct()
	{
		if (!is_null(Auth::user()))
		{
			header('Location: ' .  Router::url('home'));
			exit();
		}

		$this->varPage = [
			'title' => 'APP-PHP::INSCRIPTION',
			'h1' => 'APP-PHP',
			'h2' => 'INSCRIPTION',
			'jsFileNames' => ['confirmPassword']
		];
	}

	public function show()
	{
		$this->varPage['recordedInputs'] = Helper::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();
		$this->renderer('InscriptionView', 'show');
	}

	public function record()
	{
		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
		{
			Helper::recordInputs(['username' => $_POST['username'], 'email' => $_POST['email']]);

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
				header('Location: ' . Router::url('connexion'));
				exit();
			}
		}

		header('Location: ' .  Router::url('inscription'));
		exit();
	}	
}
