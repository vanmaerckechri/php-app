<?php

namespace App\Model;

use Core\AbstractModel;

class Category extends AbstractModel
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