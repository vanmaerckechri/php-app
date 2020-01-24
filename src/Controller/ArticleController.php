<?php

namespace App\Controller;

use Core\Router\Router;
use Core\AbstractController;
use Core\Pagination;
use App\Model\Article;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::ARTICLE',
		'h1' => 'APP-PHP',
		'h2' => 'Article',
	];

	public function show(int $id, string $slug): void
	{
		$article = new Article();
		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			$article = ArticleRepository::findOneByCol('id', $id);
			if (!is_null($article) && $article->getSlug() === $slug)
			{
				$createdAt = $article->getCreated_at()->format('Y-m-d H:i:s');
				$this->varPage['article'] = $article;
				$this->varPage['previous'] = ArticleRepository::findNextLater(['id', 'slug'], $id, $createdAt, 'later');
				$this->varPage['next'] = ArticleRepository::findNextEarler(['id', 'slug'], $id, $createdAt, 'earlier');
				$this->renderer('ArticleView', 'show');
				return;
			}
		}
		$this->redirect('error404');
		exit();
	}	
}