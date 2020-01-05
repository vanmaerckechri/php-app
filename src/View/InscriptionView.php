<?php

namespace App\View;

Class InscriptionView
{
	public static function show($varPage)
	{
		$action = $GLOBALS['router']->url('inscription');
		ob_start();
		?>
			<form action="<?= $action ?>" method="post" id='form'>
				<label for="username">Login<input type="text" name="username" id="username" value="<?= $varPage['recordedInputs']['username'] ?? '' ?>" required><?= $varPage['messages']['usernameSms'] ?? '' ?></label>
				<label for="password">Password<input type="password" name="password" id="password" required><?= $varPage['messages']['passwordSms'] ?? '' ?></label>
				<label for="pwdConfirm">Confirm Password<input type="password" name="pwdConfirm" id="pwdConfirm" required></label>
				<label for="email">email<input type="email" name="email" id="email" value="<?= $varPage['recordedInputs']['email'] ?? '' ?>" required><?= $varPage['messages']['emailSms'] ?? '' ?></label>
				<input id="validation" class="btn" type="submit" value="INSCRIPTION">
				<?= $varPage['messages']['test'] ?? '' ?>
			</form>
		<?php

		return ob_get_clean();
	}
}