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
		<?php
		if (!$user)
		{
			?>
			<a href="<?=$GLOBALS['router']->url('connexion')?>">Mon Compte</a>
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
}