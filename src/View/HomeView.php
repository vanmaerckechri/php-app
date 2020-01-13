<?php

namespace App\View;

use App\App;

Class HomeView
{
	public static function show($varPage)
	{
		ob_start();
		?>
		<div class="container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<ul class="articles-container">
				<?php foreach ($varPage['articles'] as $article): ?>
				<li class="article">
					<h3><?=htmlentities($article->getTitle())?></h3>
					<p class="content"><?=htmlentities(App::excerpt($article->getContent(), 125))?></p>
					<p class="date"><?=$article->getCreated_at()->format('d/m/y')?></p>
					<a class="btn" href="<?= $GLOBALS['router']->url('article', ['slug' => $article->getSlug(), 'id' => $article->getId()])?>">Voir Plus</a>
				</li>
				<?php endforeach ?>
			</ul>
		</div>
		<?php
		return ob_get_clean();
	}
}