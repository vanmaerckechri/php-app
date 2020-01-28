<?php

namespace App\Controller;

use Core\AbstractController;
use Core\Pagination;
use Core\Helper;

class ArticlesController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::ARTICLES',
		'h1' => 'APP-PHP',
		'h2' => 'ARTICLES',
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
			$stmt = Helper::getPdo()->query("
				SELECT article.*, user.username as user_name 
				FROM article 
				INNER JOIN user 
				ON article.user_id = user.id 
				ORDER BY article.created_at DESC
				LIMIT $itemsByPage 
				OFFSET $firstItemIndex
			");
			$this->varPage['articles'] = $stmt->fetchAll(\PDO::FETCH_CLASS, 'App\\Model\\Article');
		}

		$this->renderer('ArticlesView', 'index');
	}	
}