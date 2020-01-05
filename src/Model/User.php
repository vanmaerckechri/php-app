<?php

namespace App\Model;

use App\Validator\Validator;

class User
{
	private $id;
	private $role;
	private $email;
	private $username;
	private $password;

	public function checkVarHealth(): bool
	{
		$essentialVariables = [$this->email, $this->username, $this->password];
		foreach ($essentialVariables as $value)
		{
			if (is_null($value))
			{
				return false;
			}
		}
		return true;
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
		$this->role = $role;

		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		if (Validator::validate('usernameSms', $username, [
			'required' => true,
			'type' => 'string',
			'minLength' => 4,
			'maxLength' => 30
		]))
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
		if (Validator::validate('passwordSms', $password, [
			'required' => true,
			'type' => 'string',
			'minLength' => 4,
			'maxLength' => 30
		]))
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
		if (Validator::validate('emailSms', $email, [
			'required' => true,
			'type' => 'email',
			'minLength' => 4,
			'maxLength' => 254
		]))
		{
			$this->email = $email;
		}

		return $this;
	}
}