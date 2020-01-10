<?php

namespace App\Model;

use App\Validator\Validator;

class Article
{
	use Model;

	private $id;
	private $user_id;
	private $title;
	private $content;
	private $created_at;

	public function __construct()
	{
		$this->created_at = time();
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

	public function getUser_id(): ?int
	{
		return $this->user_id;
	}

	public function setUser_id(int $user_id): self
	{
		$this->user_id = $user_id;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		if (Validator::validate('titleSms', $title, $this->rules['title']))
		{
			$this->title = $title;
		}
		return $this;
	}

	public function getContent(): ?string
	{
		return $this->content;
	}

	public function setContent(string $content): self
	{
		if (Validator::validate('contentSms', $content, $this->rules['content']))
		{
			$this->content = $content;
		}
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