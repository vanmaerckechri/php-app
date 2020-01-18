<?php

namespace App\Controller;

use Core\AbstractController;
use App\Model\Article;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
	private $articlesByPage = 12;
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::HOME',
			'h1' => 'APP-PHP',
			'h2' => 'HOME',
		];
	}

	public function show()
	{
		$this->varPage['articles'] = ArticleRepository::findAll("ORDER BY created_at DESC LIMIT $this->articlesByPage") ?? array();
		$this->renderer('HomeView', 'show');
	}	
}