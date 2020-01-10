<?php

namespace App\Repository;

use App\Request\Request;
use App\Model\Article;

class ArticleRepository extends Repository
{
	public static function findUserById(int $id): ?article
	{
		return self::findObjByCol('id', $id);
	}

	public static function findUserByTitle(string $title): ?article
	{
		return self::findObjByCol('title', $title);
	}

	public static function findUserByContent(string $content): ?article
	{
		return self::findObjByCol('content', $content);
	}

	public static function record(Article $article): void
	{
		$request = new Request();
		$request->insertInto('article')->values([
			'title' => $user->getTitle(),
			'content' => $user->getContent(),
		]);
	}
}