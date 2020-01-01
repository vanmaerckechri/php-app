<?php

namespace App\View;

Class InscriptionView
{
	public static function show($varPage)
	{
		$action = $GLOBALS['router']->url('inscription');
		ob_start();
		?>
			<form action="<?= $action ?>" method="post">
				<label for="login">Login<input type="text" name="login" id="login" required></label>
				<label for="pwd">Password<input type="password" name="pwd" id="pwd" required></label>
				<label for="pwdConfirm">Confirm Password<input type="password" name="pwdConfirm" id="pwdConfirm" required></label>
				<input type="submit" value="INSCRIPTION">
			</form>
		<?php

		return ob_get_clean();
	}
}