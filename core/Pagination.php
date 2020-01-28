<?php

namespace Core;

use Core\Router\Router;
use Core\Helper;

class Pagination
{
	private static $numOfPages;
	private static $currentPage;

	public static function init(string $table, int $itemByPage, int $currentPage): int
	{
		$repo = 'App\\Repository\\' . ucfirst($table) . 'Repository';
		$repo = new $repo();

		$itemsCount = $repo->countRowByCol('*');
		self::$numOfPages = (int)ceil($itemsCount / $itemByPage);
		self::$currentPage = $currentPage;

		// current page doesn't exist ?
		if (self::$currentPage < 1 || (self::$currentPage > 1 && self::$currentPage > self::$numOfPages))
		{
			return -2;
		}
		// items don't exist ?
		if (self::$numOfPages < 1)
		{
			return -1;
		}

		return (self::$currentPage - 1) * $itemByPage;
	}

	public static function getNav(int $pageBySide = 4, string $cssContainer = 'pagination-container', string $cssBtn = 'btn'): ?string
	{
		if (self::$numOfPages > 0)
		{
			$pageButtons = self::managePageButtons($pageBySide);
			return self::buildButtons($pageButtons, $cssContainer, $cssBtn);
		}
		return null;
	}

	private static function managePageButtons(int $pageBySide): array
	{
		$prevPages = array();
		// skip some pages until the first
		if (self::$currentPage > $pageBySide + 1)
		{
			$prevPages = [1, '...'];

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

		$nextPages = array();
		// skip some pages until the last
		if (self::$currentPage < (self::$numOfPages - $pageBySide))
		{
			for ($i = $pageBySide - 1; $i > 0; $i--)
			{
				array_unshift($nextPages, self::$currentPage + $i);
			}

			$nextPages = array_merge($nextPages, ['...', (int)self::$numOfPages]);
		}
		// the current page is only $pageBySide pages from the last page displays the button for these pages
		else
		{
			for ($i = 1; $i <= $pageBySide; $i++)
			{
				$next = self::$currentPage + $i;
				if ($next > self::$numOfPages)
				{
					break;
				}
				$nextPages[] = $next;
			}
		}
		return array_merge($prevPages, ['current'], $nextPages);
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
				<a class="<?=$cssBtn?>" href="<?=Router::url($route, ['page' => $previousPage])?>">PAGE PRECEDENTE</a>
			<?php endif; ?>
			<?php foreach ($pageButtons as $page): ?>
				<?php if (is_int($page)): ?>
					<a class="<?=$cssBtn?>" href="<?=Router::url($route, ['page' => $page])?>"><?=$page?></a>
				<?php elseif ($page === 'current'): ?>
					<p class="<?=$cssBtn?> disable"><?=self::$currentPage?></p>
				<?php else: ?>
					<p class="<?=$cssBtn?> disable">...</p>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if ($nextPage <= self::$numOfPages): ?>
				<a class="<?=$cssBtn?>" href="<?=Router::url($route, ['page' => $nextPage])?>">PAGE SUIVANTE</a>
			<?php endif; ?>
			</div>
		<?php
		return ob_get_clean();
	}
}