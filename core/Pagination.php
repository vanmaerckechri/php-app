<?php

namespace Core;

use Core\Router\Router;

class Pagination
{
	private static $maxPage;
	private static $currentPage;

	public static function getItems(string $table, string $column, int $itemByPage): ?array
	{
		$repo = 'App\\Repository\\' . ucfirst($table) . 'Repository';
		$repo = new $repo();

		$count = $repo->countRowByCol($column);
		self::$maxPage = ceil($count / $itemByPage);
		self::$currentPage = (int)$_GET['page'] ?? 1;

		if (self::$currentPage < 1 || self::$currentPage > self::$maxPage)
		{
			header('Location: ' . Router::url('error404'));
		}

		$offset = (self::$currentPage - 1) * $itemByPage;
		$items = $repo->findAllLimitOffset('*', 'created_at DESC', $itemByPage, $offset);

		return $items ?: null;
	}

	public static function getNav(): string
	{
		$previousPage = self::$currentPage - 1;
		$nextPage = self::$currentPage + 1;

		ob_start();
		?>
			<div>
			<?php if ($previousPage >= 1): ?>
				<a href="./?page=<?=$previousPage?>">PAGE PRECEDENTE</a>
			<?php endif; ?>
			<?php if ($nextPage <= self::$maxPage): ?>
				<a href="./?page=<?=$nextPage?>">PAGE SUIVANTE</a>
			<?php endif; ?>
			</div>
		<?php
		return ob_get_clean();
	}
}