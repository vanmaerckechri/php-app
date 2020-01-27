<?php

namespace App\View;

use Core\{
	Router\Router,
	Authentification\Auth,
	AbstractView
};

Class HeaderView extends AbstractView
{
	public static function get($varPage)
	{
		$user = Auth::user();
		ob_start();
		?>
		<h1><?=$varPage['h1'] ?? ''?></h1>
		<nav>
			<div>
				<a class="<?=self::activeCurrentPage('home')?>" href="<?=Router::url('home')?>">Accueil</a>
				<a class="<?=self::activeCurrentPage('articles')?>" href="<?=Router::url('articles', ['page' => 1])?>">Les Articles</a>
				<?php if (!$user): ?>
				<a class="<?=self::activeCurrentPage('connection')?>" href="<?=Router::url('connection')?>">Mon Compte</a>
				<?php else: ?>
				<a href="<?=Router::url('disconnect')?>">Se Déconnecter</a>
				<?php endif; ?>
			</div>
			<?php if ($user): ?>
			<div>
				<a class="<?=self::activeCurrentPage('newArticle')?>" href="<?=Router::url('newArticle')?>">Rédiger un Nouvel Article</a>
			</div>
			<?php endif; ?>
		</nav>
		<?php 
		return ob_get_clean();
	}
}