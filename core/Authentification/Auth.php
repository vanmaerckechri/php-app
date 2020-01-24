<?php

namespace Core\Authentification;

use Core\Router\Router;
use App\Model\User;
use App\Repository\UserRepository;

class Auth
{
	public static function user(): ?User
	{
		$user = new User();
		$id = $_SESSION['auth'] ?? null;
		if (!$user->isValid(['id' => $id], false))
		{
			return null;
		}
		$user = UserRepository::findOneByCol('id', $id);
		return $user;
	}

	public static function login(string $username, string $password): bool
	{
		$user = new User();
		$inputs = array('username' => $username, 'password' => $password);
		if ($user->isValid($inputs, false))
		{
			$user = UserRepository::findOneByCol('username', $username);

			if (!is_null($user) && password_verify($password, $user->getPassword()))
			{
				self::addUserToSession($user);
				return true;
			}
		}
		return false;
	}

	public static function addUserToSession(User $user): void
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