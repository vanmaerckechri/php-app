<?php

namespace App\View;

use App\Authentification\Auth;

Class HeaderView
{
	public static function get($varPage)
	{
		$user = Auth::user();
		ob_start();
		?>
		<h1><?=$varPage['h1'] ?? ''?></h1>
		<nav>
			<a class="<?=self::getActive('')?>" href="<?=$GLOBALS['router']->url('home')?>">Les Articles</a>
		<?php
		if (!$user)
		{
			?>
			<a class="<?=self::getActive('connexion')?>" href="<?=$GLOBALS['router']->url('connexion')?>">Mon Compte</a>
			<?php
		}
		else
		{
			?>
			<a href="<?=$GLOBALS['router']->url('disconnect')?>">Se Déconnecter</a>
			<?php
		}
		?>
		</nav>
		<?php
		return ob_get_clean();
	}

	private static function getActive(string $route): ?string
	{
		$url = $_GET['url'];
		if ($url === $route)
		{
			return 'active';
		}
		return null;
	}
}