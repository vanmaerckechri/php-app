<?php

namespace App\Model;

use App\Validator\Validator;

class User extends Model
{
	private $id;
	private $role;
	private $email;
	private $username;
	private $password;
	private $created_at;

	public function __construct()
	{
		$this->created_at = new \DateTime('now');
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

    public function getCreated_at(): ?string
    {
        return $this->created_at->format('Y-m-d H:i:s');
    }

    public function setCreated_at(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}