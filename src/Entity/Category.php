<?php

namespace App\Entity;

use Core\AbstractEntity;

class Category extends AbstractEntity
{
	private $id;
	private $title;

	public function __construct()
	{
		parent::__construct(__CLASS__);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(int $id): self
	{
		$this->id = $id;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}
}