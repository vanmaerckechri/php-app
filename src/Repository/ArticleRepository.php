<?php

namespace App\Repository;

use Core\ {
	Request,
	AbstractRepository
};
use App\Model\Article;

class ArticleRepository extends AbstractRepository
{
	public static function findArticleById(int $id): ?article
	{
		return self::findObjByCol('id', $id);
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