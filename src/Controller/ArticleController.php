<?php

namespace App\Controller;

use App\Model\Article;
use App\Repository\ArticleRepository;

Class ArticleController extends ViewManager
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::ARTICLE',
			'h1' => 'APP-PHP',
			'h2' => 'Article',
		];
	}

	public function show($id, $slug)
	{
		$article = new Article();
		if ($article->isValid(['id' => $id, 'slug' => $slug]))
		{
			$this->varPage['article'] = ArticleRepository::findArticleByIdSlug(['id' => $id, 'slug' => $slug]);
			if (!is_null($this->varPage['article']))
			{
				$this->varPage['id'] = $id;
				$this->varPage['slug'] = $slug;
				$this->renderer(['ArticleView', 'show']);
				return;
			}
		}
		header('Location: ' . $GLOBALS['router']->url('error404'));
		exit();
	}	
}