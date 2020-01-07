<?php

namespace App\Model;

use App\Validator\Validator;
use App\Request\UserRequest;
use App\Schema\UserSchema;

class User
{
	use Model;

	private $id;
	private $role;
	private $email;
	private $username;
	private $password;

	public function __construct()
	{
		$this->initValidationRules('User', new UserRequest, UserSchema::getSchema());
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function setRole(string $role): self
	{
		if (Validator::validate('roleSms', $role, $this->rules['role']))
		{
			$this->role = $role;
		}

		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		if (Validator::validate('usernameSms', $username, $this->rules['username']))
		{
			$this->username = $username;
		}
		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		if (Validator::validate('passwordSms', $password, $this->rules['password']))
		{
			$this->password = password_hash($password, PASSWORD_DEFAULT);
		}

		return $this;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		if (Validator::validate('emailSms', $email, $this->rules['email']))
		{
			$this->email = $email;
		}

		return $this;
	}

	public function isValidToLoginByUsername()
	{
		return $this->isValid(['username', 'password']);
	}
}