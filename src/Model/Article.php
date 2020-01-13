<?php

namespace App\Model;

use App\Validator\Validator;

class Article extends Model
{
	private $id;
	private $user_id;
	private $slug;
	private $title;
	private $content;
	private $created_at;

	public function __construct()
	{
		parent::__construct(__CLASS__);
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function setId(string $id): self
	{
		$this->id = $id;

		return $this;
	}

	public function getUser_id(): ?int
	{
		return $this->user_id;
	}

	public function setUser_id(int $user_id): self
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getSlug(): ?string
	{
		return $this->slug;
	}

	public function setSlug(string $slug): self
	{
		$this->slug = $slug;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;
		$this->slug = App::slugify($title);

		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		$this->content = $content;

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