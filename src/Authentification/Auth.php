<?php

namespace App\Authentification;

use PDO;
use App\App;
use App\Model\User;
use App\Request\UserRequest;

class Auth
{
	public static function user(): ?User
	{
		$id = $_SESSION['auth'] ?? null;
		if ($id === null)
		{
			return null;
		}

		$userRequest = new UserRequest();
		return $userRequest->findUserById($id);
	}

	public static function login(string $username, string $password): ?User
	{
		if (App::hydrateModel(new User(), ['username' => $username, 'password' => $password]))
		{
			$userRequest = new UserRequest();
			$user = $userRequest->findUserByUsername($username);
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