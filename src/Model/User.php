<?php

namespace App\Model;

use App\Validator\Validator;

class User
{
	private $id;
	private $role;
	private $username;
	private $password;

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
			'minLength' => 5,
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
			'minLength' => 8,
			'maxLength' => 30
		]))
		{
			$this->password = $password;
		}

		return $this;
	}
}