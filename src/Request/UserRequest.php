<?php

namespace App\Request;

use App\Model\User;

class UserRequest
{
	use Request;

	public function findUserById(int $id): ?User
	{
		$stmt = $this->select(
			'SELECT * FROM user WHERE id = :id',
			['id' => [$id, 'int']]
		);
		//$stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function findUserByUsername(string $username): ?User
	{
		$stmt = $this->select(
			'SELECT * FROM user WHERE username = :username',
			['username' => [$username, 'str']]
		);
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function findUserByEmail(string $email): ?User
	{
		$stmt = $this->select(
			'SELECT * FROM user WHERE email = :email',
			['email' => [$email, 'str']]
		);
		$user = $stmt->fetchObject(User::class);
		return $user ?: null;
	}

	public function record(User $user): void
	{
		$this->Insert(
			'INSERT INTO user (email, username, password) VALUES (:email, :username, :password)',
			[
				'email' => [$user->getEmail(), 'str'],
				'username' => [$user->getUsername(), 'str'],
				'password' => [$user->getPassword(), 'str'],
			]
		);
	}

	public function recordOauth(User $user): void
	{
		$this->Insert(
			'INSERT INTO user (email) VALUES (:email)',
			[
				'email' => [$user->getEmail(), 'str'],
			]
		);	
	}
}