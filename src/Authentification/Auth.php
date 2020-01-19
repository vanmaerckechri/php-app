<?php

namespace App\Authentification;

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
		$user = UserRepository::findUserById($id);
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
		header('Location: ' . Router::url('home'));
		exit();		
	}
}