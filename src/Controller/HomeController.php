<?php

namespace App\Controller;

use Core\AbstractController;
use Core\Pagination;
use App\Model\Article;
use App\Repository\ArticleRepository;

class HomeController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::HOME',
		'h1' => 'APP-PHP',
		'h2' => 'HOME',
	];

	public function index()
	{
		$this->varPage['articles'] = Pagination::getItems('article', 'created_at', 12);
		$this->renderer('HomeView', 'index');
	}	
}