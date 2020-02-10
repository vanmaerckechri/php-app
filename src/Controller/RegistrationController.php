<?php

namespace App\Controller;

use Core\ {
	App,
	AbstractController,
	MessagesManager\MessagesManager
};
use App\ {
	Entity\User,
	Repository\UserRepository,
	Mail\RegistrationMail
};

class RegistrationController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::INSCRIPTION',
		'h1' => 'APP-PHP',
		'h2' => 'INSCRIPTION',
		'css' => ['style']
	];

	public function new(): void
	{
		$this->redirect('home', ['logged' => true]);

		$this->varPage['js'] = ['ConfirmPassword'];
		$this->varPage['script'] = self::scriptForNew();

		$this->varPage['recordedInputs'] = $this->getRecordedInputs();
		$this->varPage['messages'] = MessagesManager::getMessages();

		$this->renderer('RegistrationView', 'new');
	}

	public function create(): void
	{
		$this->redirect('home', ['logged' => true]);

		if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email']))
		{
			$this->recordInputs(['username' => $_POST['username'], 'email' => $_POST['email']]);

			$user = new User();
			$isValid = false;

			if ($user->isValid(['email' => $_POST['email'], 'username' => $_POST['username']]))
			{
				if ($user->isUnique(['email', 'username']))
				{
					$isValid = true;
				}				
			}

			if (!$user->isValid(['password' => $_POST['password']]))
			{
				$isValid = false;
			}

			if ($isValid)
			{
				$token = md5(microtime(TRUE)*100000);
				$user->setToken($token);
				RegistrationMail::send($_POST['email'], ['token' => $token]);

				UserRepository::record($user);
				MessagesManager::add(['info' => ['registerComplete' => null]]);
				$this->redirect('connection');
			}
		}
		
		$this->redirect('registration');
	}

	public function validation(string $token): void
	{
		$this->redirect('home', ['logged' => true]);

		$userWithNewInputs = new user();
		if ($userWithNewInputs->isValid(['token' => $token]))
		{
			$user = UserRepository::findOneByCol('token', $token);
			if ($user)
			{
				$status = $user->getStatus();
				if ($status < 2)
				{
					$userWithNewInputs->setStatus(2);

					$id = $user->getId();
					UserRepository::updateById($userWithNewInputs, $id);
					$this->recordInputs(['username' => $user->getUsername()]);
					MessagesManager::add(['info' => ['accountActivated' => null]]);
					$this->redirect('connection');
				}
			}
			else
			{
				MessagesManager::add(['info' => ['tokenInvalid' => null]]);
			}
		}
		$this->redirect('connection');
	}

	private static function scriptForNew(): string
	{
		ob_start(); ?>
			CVMTOOLS.confirmPassword = new CVMTOOLS.ConfirmPassword();
			CVMTOOLS.confirmPassword.init('form');
		<?php return ob_get_clean();
	}
}
