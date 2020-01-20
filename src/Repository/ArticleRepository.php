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
}