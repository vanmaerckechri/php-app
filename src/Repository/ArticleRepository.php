<?php

namespace App\Repository;

use Core\ {
	AbstractRepository,
	Request
};

class ArticleRepository extends AbstractRepository
{
	static function findArticlesByPage(int $itemsByPage, int $firstItemIndex)
	{
		$request = new Request();
		$output = $request
			->select('article.*, user.username AS user_name, user.role AS user_role')
			->from('article')
			->innerJoin('user')
			->on('article.user_id = user.id')
			->orderBy('article.created_at DESC')
			->limit($itemsByPage)
			->offset($firstItemIndex)
			->fetchAllClass();

		return $output ?: null;
	}

	static function findArticleById(int $id)
	{
		$request = new Request();
		$output = $request
			->select('article.*, user.username AS user_name, user.role AS user_role')
			->from('article')
			->innerJoin('user')
			->on('article.user_id = user.id')
			->where('article.id', '=', $id)
			->fetchClass();

		return $output ?: null;
	}
}