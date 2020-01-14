<?php

namespace App\Model;

use App\Validator\Validator;

class User extends AbstractModel
{
	private $id;
	private $role;
	private $email;
	private $username;
	private $password;
	private $created_at;

	public function __construct()
	{
		parent::__construct(__CLASS__);
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

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

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
}