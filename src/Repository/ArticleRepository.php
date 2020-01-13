<?php

namespace App\Repository;

use App\Request\Request;
use App\Model\Article;

class ArticleRepository extends Repository
{
	public static function findArticleByIdSlug(array $values): ?article
	{
		$request = new Request();
		$obj = $request
			->select('*')
			->from('Article')
			->where('id', $values['id'])
			->and('slug', $values['slug'])
			->fetchClass();

		return $obj ?: null;
	}

	public static function findArticleByTitle(string $title): ?article
	{
		return self::findObjByCol('title', $title);
	}

	public static function findArticleByContent(string $content): ?article
	{
		return self::findObjByCol('content', $content);
	}
}