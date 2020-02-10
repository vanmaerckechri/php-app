<?php

namespace App\View;

use Core\{
	AbstractView,
	Router\Router,
	Authentification\Auth
};

Class HeaderView extends AbstractView
{
	public static function get(array $varPage): string
	{
		$user = Auth::user();
		ob_start(); ?>
		<h1><?=$varPage['h1'] ?? ''?></h1>
		<nav>
			<?php if ($user): ?>
			<div>
				<a class="<?=self::activeCurrentPage('newArticle')?>" href="<?=Router::url('newArticle')?>">Rédiger un Nouvel Article</a>
			</div>
			<?php endif; ?>
			<div class="btn-container">
				<a class="<?=self::activeCurrentPage('home')?>" href="<?=Router::url('home')?>">Accueil</a>
				<a class="<?=self::activeCurrentPage('articles')?>" href="<?=Router::url('articles', ['page' => 1])?>">Les Articles</a>
				<?php if (!$user): ?>
				<a class="<?=self::activeCurrentPage('connection')?>" href="<?=Router::url('connection')?>">Mon Compte</a>
				<?php else: ?>
				<a href="<?=Router::url('disconnect')?>">Se Déconnecter</a>
				<?php endif; ?>
			</div>
		</nav>
		<?php return ob_get_clean();
	}
}