<?php

namespace App\Entity;

use Core\AbstractEntity;

class User extends AbstractEntity
{
	private $id;
	private $email;
	private $username;
	private $password;
	private $role;
	private $created_at;
	private $status;
	private $token;

	public function __construct()
	{
		parent::__construct(__CLASS__);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getUsername(): ?string
	{
		return $this->username;
	}

	public function setUsername(string $username): self
	{
		$this->username = $username;

		return $this;
	}

	public function getPassword(): ?string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = password_hash($password, PASSWORD_DEFAULT);

		return $this;
	}

	public function getRole(): ?int
	{
		return $this->role;
	}

	public function setRole(int $role): self
	{
		$this->role = $role;

		return $this;
	}

	public function getCreated_at(): ?\DateTime
	{
		if (is_null($this->created_at))
		{
			return null;
		}
		return new \DateTime($this->created_at);
	}

	public function getStatus(): ?int
	{
		return $this->status;
	}

	public function setStatus(int $status): self
	{
		$this->status = $status;

		return $this;
	}

	public function getToken(): ?string
	{
		return $this->token;
	}

	public function setToken(?string $token): self
	{
		$this->token = $token;

		return $this;
	}
}