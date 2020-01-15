<?php

namespace App\Repository;

use Core\AbstractRepository;
use App\Model\User;

class UserRepository extends AbstractRepository
{
	public static function findUserById(int $id): ?User
	{
		return self::findObjByCol('id', $id);
	}

	public static function findUserByEmail(string $email): ?User
	{
		return self::findObjByCol('email', $email);
	}

	public static function findUserByUsername(string $username): ?User
	{
		return self::findObjByCol('username', $username);
	}
}