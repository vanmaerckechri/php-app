<?php

namespace App\Authentification;

use PDO;
use App\App;
use App\Model\User;
use App\Repository\UserRepository;
use App\Validator\Validator;

class Auth
{
	public static function user(): ?User
	{
		$user = new User();
		if (!$user->isValid(['id' => $_SESSION['auth']], false))
		{
			return null;
		}
		$user = UserRepository::findUserById($_SESSION['auth']);
		return $user;
	}

	public static function login(string $username, string $password): ?User
	{
		$user = new User();
		$inputs = array('username' => $username, 'password' => $password);
		if ($user->isValid($inputs, false))
		{
			$user = UserRepository::findUserByUsername($username);

			if ($user === null)
			{
				return null;
			}

			if (password_verify($password, $user->getPassword()))
			{
				self::addUserToSession($user);
			}
		}
		return null;
	}

	public static function addUserToSession(User $user): void
	{
		$_SESSION['auth'] = $user->getId();
		header('Location: ' . $GLOBALS['router']->url('home'));
		exit();		
	}
}