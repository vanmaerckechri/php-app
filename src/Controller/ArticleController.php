<?php

namespace App\Controller;

use Core\Router\Router;
use Core\AbstractController;
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
				$this->varPage['article'] = $article;
				$this->renderer('ArticleView', 'show');
				return;
			}
		}
		$this->redirect('error404');
		exit();
	}	
}