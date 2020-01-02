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
				<label for="username">Login<input type="text" name="username" id="username" required></label>
				<?= $varPage['errors']['usernameSms'] ?? '' ?>
				<label for="password">Password<input type="password" name="password" id="password" required></label>
				<label for="pwdConfirm">Confirm Password<input type="password" name="pwdConfirm" id="pwdConfirm" required></label>
				<?= $varPage['errors']['passwordSms'] ?? '' ?>
				<input type="submit" value="INSCRIPTION">
			</form>
		<?php

		return ob_get_clean();
	}
}