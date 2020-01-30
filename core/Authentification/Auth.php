<?php

namespace Core\Authentification;

use Core\ {
	App,
	Router\Router
};

class Auth
{
	public static function user(): ?object
	{
		$entity = App::getClass('entity', 'user');
		$user = new $entity();
		$id = $_SESSION['auth'] ?? null;
		if (!$user->isValid(['id' => $id], false))
		{
			return null;
		}
		$repo = App::getClass('repository', 'user');
		$user = call_user_func_array([$repo, 'findOneByCol'], ['id', $id]);
		return $user;
	}

	public static function login(string $username, string $password): bool
	{
		$entity = App::getClass('entity', 'user');
		$user = new $entity();
		$inputs = array('username' => $username, 'password' => $password);
		if ($user->isValid($inputs, false))
		{
			$repo = App::getClass('repository', 'user');
			$user = call_user_func_array([$repo, 'findOneByCol'], ['username', $username]);

			if (!is_null($user) && password_verify($password, $user->getPassword()))
			{
				self::addUserToSession($user);
				return true;
			}
		}
		return false;
	}

	public static function addUserToSession(object $user): void
	{
		$_SESSION['auth'] = $user->getId();
	}

	public static function removeUserFromSession(): bool
	{
		if (isset($_SESSION['auth']) && !is_null($_SESSION['auth']))
		{
			$_SESSION['auth'] = null;
			return true;
		}
		return false;
	}
}