<?php

namespace App\View;

use Core\AbstractView;
use Core\Router\Router;
use App\Authentification\Auth;

Class HeaderView extends AbstractView
{
	public static function get($varPage)
	{
		$user = Auth::user();
		ob_start();
		?>
		<h1><?=$varPage['h1'] ?? ''?></h1>
		<nav>
			<a class="<?=self::activeCurrentPage('home')?>" href="<?=Router::url('home')?>">Les Articles</a>
		<?php if (!$user): ?>
			<a class="<?=self::activeCurrentPage('connexion')?>" href="<?=Router::url('connexion')?>">Mon Compte</a>
		<?php else: ?>
			<a href="<?=Router::url('disconnect')?>">Se Déconnecter</a>
		<?php endif; ?>
		</nav>
		<?php 
		return ob_get_clean();
	}
}