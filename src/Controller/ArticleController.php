<?php

namespace App\Controller;

use Core\AbstractController;
use App\Model\Article;
use App\Repository\ArticleRepository;

class ArticleController extends AbstractController
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::ARTICLE',
			'h1' => 'APP-PHP',
			'h2' => 'Article',
		];
	}

	public function show(int $id, string $slug): void
	{
		$article = new Article();
		if ($article->isValid(['id' => $id, 'slug' => $slug], false))
		{
			$this->varPage['article'] = ArticleRepository::findArticleByIdSlug(['id' => $id, 'slug' => $slug]);
			if (!is_null($this->varPage['article']))
			{
				$this->varPage['id'] = $id;
				$this->renderer('ArticleView', 'show');
				return;
			}
		}
		header('Location: ' . $GLOBALS['router']->url('error404'));
		exit();
	}	
}