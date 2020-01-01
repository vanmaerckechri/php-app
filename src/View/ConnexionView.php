<?php

namespace App\View;

Class ConnexionView
{
	public static function show($varPage)
	{
		$action = $GLOBALS['router']->url('connexion');
		$inscription = $GLOBALS['router']->url('inscription');
		ob_start();
		?>
			<form action="<?= $action ?>" method="post">
				<label for="login">Login<input type="text" name="login" id="login" required></label>
				<label for="pwd">Password<input type="password" name="pwd" id="pwd" required></label>
				<input type="submit" value="CONNEXION">
			</form>
			<a href="<?= $inscription ?>">Vous n'avez pas encore de compte ?</a>
		<?php

		return ob_get_clean();
	}
}