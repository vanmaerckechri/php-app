<?php

namespace Core;

use Core\Router\Router;

class Pagination
{
	private static $maxPage;
	private static $currentPage;

	public static function getItems(string $table, string $column, int $itemByPage, int $page, string $redirectRoute, array $routeParams = []): array
	{
		$repo = 'App\\Repository\\' . ucfirst($table) . 'Repository';
		$repo = new $repo();

		$count = $repo->countRowByCol($column);

		self::$maxPage = ceil($count / $itemByPage);
		self::$currentPage = isset($page) ? $page : 1;

		if (self::$currentPage < 1 || self::$currentPage > self::$maxPage)
		{
			if (self::$maxPage > 0)
			{
				header('Location: ' . Router::url($redirectRoute, $routeParams));
				exit;
			}
			return [];
		}

		$offset = (self::$currentPage - 1) * $itemByPage;
		$items = $repo->findAllLimitOffset('*', 'created_at DESC', $itemByPage, $offset);

		return $items;
	}

	public static function getNav(int $pageBySide = 4, string $cssContainer = 'pagination-container', string $cssBtn = 'btn'): ?string
	{
		if (self::$maxPage > 0)
		{
			$pageButtons = self::managePageButtons($pageBySide);
			return self::buildButtons($pageButtons, $cssContainer, $cssBtn);
		}
		return null;
	}

	private static function managePageButtons(int $pageBySide): array
	{
		$prevPages = array();
		$nextPages = array();

		// skip some pages until the first
		if (self::$currentPage > $pageBySide + 1)
		{
			$prevPages = [1, null];

			for ($i = $pageBySide - 1; $i > 0; $i--)
			{
				$prevPages[] = self::$currentPage - $i;
			}
		}
		// current page is only $pageBySide pages from the first page displays the button for these pages
		else
		{
			for ($i = 1; $i < $pageBySide + 1; $i++)
			{
				$prev = self::$currentPage - $i;
				if ($prev < 1)
				{
					break;
				}
				array_unshift($prevPages, $prev);
			}
		}
		$prevPages[] = 'current';

		// skip some pages until the last
		if (self::$currentPage < (self::$maxPage - $pageBySide))
		{
			for ($i = $pageBySide - 1; $i > 0; $i--)
			{
				array_unshift($nextPages, self::$currentPage + $i);
			}

			$nextPages = array_merge($nextPages, [null, (int)self::$maxPage]);
		}
		// the current page is only $pageBySide pages from the last page displays the button for these pages
		else
		{
			for ($i = 1; $i <= $pageBySide; $i++)
			{
				$next = self::$currentPage + $i;
				if ($next > self::$maxPage)
				{
					break;
				}
				$nextPages[] = $next;
			}
		}
		return array_merge($prevPages, $nextPages);
	}

	private static function buildButtons(array $pageButtons, string $cssContainer, string $cssBtn): string
	{
		$previousPage = self::$currentPage - 1;
		$nextPage = self::$currentPage + 1;
		$route = Router::getCurrentRouteName();

		ob_start();
		?>
			<div class="<?=$cssContainer?>">
			<?php if ($previousPage >= 1): ?>
				<a class="<?=$cssBtn?>" href="<?="{$route}{$previousPage}"?>">PAGE PRECEDENTE</a>
			<?php endif; ?>
			<?php foreach ($pageButtons as $page): ?>
				<?php if (is_int($page)): ?>
					<a class="<?=$cssBtn?>" href="<?="{$route}{$page}"?>"><?=$page?></a>
				<?php elseif (is_string($page) && $page === 'current'): ?>
					<p class="<?=$cssBtn?> disable"><?=self::$currentPage?></p>
				<?php else: ?>
					<p class="<?=$cssBtn?> disable">...</p>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if ($nextPage <= self::$maxPage): ?>
				<a class="<?=$cssBtn?>" href="<?="{$route}{$nextPage}"?>">PAGE SUIVANTE</a>
			<?php endif; ?>
			</div>
		<?php
		return ob_get_clean();
	}
}