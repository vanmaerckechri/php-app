<?php

namespace App\Controller;

use Core\ {
	Helper,
	AbstractController,
	MessagesManager\MessagesManager
};
use App\Model\User;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
	public function __construct()
	{
		$this->redirect('home', ['logged' => true]);

		$this->varPage = [
			'title' => 'APP-PHP::INSCRIPTION',
			'h1' => 'APP-PHP',
			'h2' => 'INSCRIPTION',
			'jsFileNames' => ['confirmPassword']
		];
	}

	public function new()
	{
		$this->varPage['recordedInputs'] = Helper::getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();

		$this->renderer('RegistrationView', 'new');
	}

	public function create()
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
				$this->redirect('connection');
				exit();
			}
		}

		$this->redirect('registration');
		exit();
	}	
}