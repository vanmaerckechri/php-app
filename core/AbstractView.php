<?php

namespace Core;

use Core\Router\Router;

abstract class AbstractView
{
	protected static function activeCurrentPage(string $route): ?string
	{
		if (Router::isCurrentRoute($route))
		{
			return 'active';
		}
		return null;
	}
}