<?php

namespace App\View;

use Core\{
	Router\Router,
	AbstractView,
	Pagination
};

Class ArticlesView extends AbstractView
{
	public static function index($varPage)
	{
		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<ul class="articles-container">
				<?php foreach ($varPage['articles'] as $article): ?>
				<li class="article">
					<h3><?=htmlentities($article->getTitle())?></h3>
					<p class="content"><?=htmlentities(self::excerpt($article->getContent(), 125))?></p>
					<p class="date"><?=$article->getCreated_at()->format('d/m/y')?></p>
					<a class="btn" href="<?=Router::url('article', ['slug' => $article->getSlug(), 'id' => $article->getId()])?>">Voir Plus</a>
				</li>
				<?php endforeach ?>
			</ul>
			<?=Pagination::getNav(4) ?: 'Aucun article trouvé!';?>
		</div>
		<?php
		return ob_get_clean();
	}
}