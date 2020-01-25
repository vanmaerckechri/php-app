<?php

namespace Core;

use Core\Router\Router;

abstract class AbstractView
{
	protected static function activeCurrentPage(string $route): ?string
	{
		if (Router::isRouteForUrl($route, $_GET['url']))
		{
			return 'active';
		}
		return null;
	}

	protected static function excerpt(string $content, int $limit = 60): string
	{
		if (mb_strlen($content) > $limit)
		{
			$lastSpacePos = mb_strpos($content, ' ', $limit);
			if (!is_int($lastSpacePos))
			{
				$lastSpacePos = $limit;
			}
			return mb_substr($content, 0, $lastSpacePos) . '...';
		}
		return $content;
	}
}