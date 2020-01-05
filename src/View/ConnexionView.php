<?php

namespace App\View;

Class ConnexionView
{
	public static function show($varPage)
	{
		ob_start();
		?>
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<form action="" method="post">
				<label for="username">Nom d'Utilisateur<input type="text" name="username" id="username" value="<?= $varPage['recordedInputs']['username'] ?? '' ?>" required></label>
				<label for="password">Mot de Passe<input type="password" name="password" id="password" required><?= $varPage['messages']['authSms'] ?? '' ?></label>
				<input class="btn" type="submit" value="CONNEXION">
				<a href="<?= $varPage['inscriptionUrl'] ?>">Vous n'avez pas encore de compte ?</a>
				<a class="btn" href="https://accounts.google.com/o/oauth2/v2/auth?scope=profile email&access_type=online&redirect_uri=<?= $varPage['googleCoUri'] ?>&response_type=code&client_id=<?= $varPage['google_id'] ?>">SE CONNECTER AVEC GOOGLE</a>
			</form>
		<?php

		return ob_get_clean();
	}
}