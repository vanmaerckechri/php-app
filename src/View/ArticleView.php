<?php

namespace App\View;

Class ArticleView
{
	public static function show($varPage)
	{
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
		</div>
		<?php 
		return ob_get_clean();
	}
}