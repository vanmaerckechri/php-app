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
			'INSERT INTO user (email, username, password) VALUES (:email, :username, :password) ON DUPLICATE KEY UPDATE username = username + 1',
			[
				'email' => [$user->getEmail(), 'str'],
				'username' => [$user->getUsername(), 'str'],
				'password' => [$user->getPassword(), 'str'],
			]
		);
	}

	public function incrementIfTaken(string $column, string $type, string $value): ?string
	{
		$newValue = $value;
		for ($i = 100; $i >= 0; --$i)
		{
			$stmt = $this->select(
				"SELECT * FROM user WHERE $column = :$column",
				[$column => [$newValue, $type]]
			);
			$user = $stmt->fetchObject(User::class);
			if (!$user)
			{
				return $newValue;
			}
			$newValue = !is_numeric(substr($newValue, -1)) ? $newValue . '1' : ++$newValue;
		}
		return null;
	}
}