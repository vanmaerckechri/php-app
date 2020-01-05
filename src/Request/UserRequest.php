<?php

namespace App\Request;

use PDO;
use App\App;
use App\Model\User;

class UserRequest
{
	public function find()
	{

	}

	public function findUserById(int $id): ?User
	{

		$stmt = App::getPdo()->prepare("SELECT * FROM user WHERE id = :id");
		$stmt->bindValue(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
		//$stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function findUserByUsername(string $username): ?User
	{
		$stmt = App::getPdo()->prepare("SELECT * FROM user WHERE username = :username");
		$stmt->bindValue(':username', $username, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function findUserByEmail(string $email): ?User
	{
		$stmt = App::getPdo()->prepare("SELECT * FROM user WHERE email = :email");
		$stmt->bindValue(':email', $email, PDO::PARAM_STR);
		$stmt->execute();
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function record(User $user): ?array
	{
		$userByEmail = $this->findUserByEmail($user->getEmail());
		$userByUsername = $this->findUserByUsername($user->getUsername());

		if (!$userByEmail && !$userByUsername)
		{
			$stmt = App::getPdo()->prepare("INSERT INTO user (email, username, password) VALUES (:email, :username, :password)");
			$stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
			$stmt->bindValue(':username', $user->getUsername(), PDO::PARAM_STR);
			$stmt->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
			$stmt->execute();
			return null;
		}
		else
		{
			$columnConflict = array();
			if ($userByUsername && $userByUsername->getUsername() === $user->getUsername())
			{
				$columnConflict[] = 'username';
			}
			if ($userByEmail && $userByEmail->getEmail() === $user->getEmail())
			{
				$columnConflict[] = 'email';
			}
			return $columnConflict;
		}
	}

	public function recordOauth(User $user): void
	{
		$stmt = App::getPdo()->prepare("INSERT INTO user (email) VALUES (:email)");
		$stmt->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
		$stmt->execute();		
	}
}