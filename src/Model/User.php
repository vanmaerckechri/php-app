<?php

namespace App\Model;

use App\Validator\Validator;

class User
{
	use Model;

	private $id;
	private $role;
	private $email;
	private $username;
	private $password;

	private $rules = array(
		'role' => array(
			'only' => array('user', 'admin')
		),
		'email' => array(
			'required' => true,
			'type' => 'email',
			'minLength' => 4,
			'maxLength' => 254
		),
		'username' => array(
			'required' => true,
			'type' => 'string',
			'minLength' => 4,
			'maxLength' => 30
		),
		'password' => array(
			'required' => true,
			'type' => 'string',
			'minLength' => 4,
			'maxLength' => 254
		)
	);

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