<?php

namespace App\Controller;

use Core\AbstractController;
use Core\Pagination;

class ArticlesController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::ARTICLES',
		'h1' => 'APP-PHP',
		'h2' => 'ARTICLES',
	];

	public function index($page)
	{
		$articles = Pagination::getItems('article', 'created_at DESC', 12, $page);
		if (is_null($articles))
		{
			$this->redirect('error404');
		}
		$this->varPage['articles'] = $articles;
		$this->renderer('ArticlesView', 'index');
	}	
}