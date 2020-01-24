<?php

namespace App\View;

use Core\Router\Router;

Class ArticleView
{
	public static function show($varPage)
	{
		$previous = $varPage['previous'];
		$next = $varPage['next'];

		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<ul>
				<li>
					<h3><?=htmlentities($varPage['article']->getTitle())?></h3>
					<p><?=nl2br(htmlentities($varPage['article']->getContent()))?></p>
					<p><?=$varPage['article']->getCreated_at()->format('d/m/y')?></p>
				</li>
			</ul>
			<div class="pagination-container">
				<?php if ($previous): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $previous->getId(), 'slug' => $previous->getSlug()])?>">PREVIOUS</a>
				<?php endif; ?>
				<?php if ($next): ?>
					<a class="btn" href="<?=Router::url('article', ['id' => $next->getId(), 'slug' => $next->getSlug()])?>">NEXT</a>
				<?php endif; ?>
			</div>
		</div>
		<?php 
		return ob_get_clean();
	}
}