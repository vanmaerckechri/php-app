<?php

namespace App\Request;

use PDO;
use App\App;
use App\Model\User;
use App\ErrorsManager;

class UserRequest
{
	public function record(User $user): bool
	{
		$stmt = App::getPdo()->prepare("SELECT id FROM user WHERE username = :username");
		$stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
		$stmt->execute();
		if (!$stmt->fetchObject(User::class))
		{
			$stmt = App::getPdo()->prepare("INSERT INTO user (username, password) VALUES (:username, :password)");
			$stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
			$stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
			$stmt->execute();
			return true;
		}
		else
		{
			return false;
		}
	}
}