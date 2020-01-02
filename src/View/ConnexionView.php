<?php

namespace App\View;

Class ConnexionView
{
	public static function show($varPage)
	{
		$inscription = $GLOBALS['router']->url('inscription');
		ob_start();
		?>
			<form action="" method="post">
				<label for="username">Nom d'Utilisateur<input type="text" name="username" id="username" value=<?= $varPage['username'] ?? '""' ?> required></label>
				<label for="password">Mot de Passe<input type="password" name="password" id="password" required><?= $varPage['messages']['authSms'] ?? '' ?></label>
				<input class="btn" type="submit" value="CONNEXION">
				<?= $varPage['messages']['registerComplete'] ?? '' ?>
				<a href="<?= $inscription ?>">Vous n'avez pas encore de compte ?</a>
			</form>
		<?php

		return ob_get_clean();
	}
}