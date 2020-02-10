<?php

namespace App\Controller;

use Core\ {
	Pagination,
	AbstractController
};
use App\Repository\ArticleRepository;

class ArticlesController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::ARTICLES',
		'h1' => 'APP-PHP',
		'h2' => 'ARTICLES',
		'css' => ['style']
	];

	public function index($currentPage)
	{
		$itemsByPage = 12;

		// Pagination
		$firstItemIndex = Pagination::init('article', $itemsByPage, $currentPage);
		// current page doesn't exist ?
		if ($firstItemIndex === -2)
		{
			$this->redirect('error404', ['code' => 404]);
		}
		// if items don't exist ?
		else if ($firstItemIndex === -1)
		{
			$this->varPage['articles'] = [];
		}
		else
		{
			$this->varPage['articles'] = ArticleRepository::findArticlesByPage($itemsByPage, $firstItemIndex);
		}

		$this->renderer('ArticlesView', 'index');
	}	
}