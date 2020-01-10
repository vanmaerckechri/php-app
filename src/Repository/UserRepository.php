<?php

namespace App\Repository;

use App\Request\Request;
use App\Model\User;

class UserRepository extends Repository
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