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
		$this->varPage['articles'] = Pagination::getItems('article', 'created_at', 12, $page, 'error404');
		$this->renderer('ArticlesView', 'index');
	}	
}