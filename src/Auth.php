<?php

namespace App;

use PDO;
use App\App;
use App\Model\User;

class Auth
{
	public static function user(): ?User
	{
		$id = $_SESSION['auth'] ?? null;
		if ($id === null)
		{
			return null;
		}

		$stmt = App::getPdo()->prepare("SELECT * FROM user WHERE id = :id");
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public static function login(string $username, string $password): ?User
	{
		$stmt = App::getPdo()->prepare("SELECT * FROM user WHERE username = :username");
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		//$stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
		$user = $stmt->fetchObject(User::class);
		if ($user === false)
		{
			return null;
		}

		if (password_verify($password, $user->getPassword()))
		{
			$_SESSION['auth'] = $user->getId();
			header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('home'));
			exit();
		}
		return null;
	}
}