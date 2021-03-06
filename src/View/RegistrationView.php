<?php

namespace App\View;

use Core\ {
	AbstractView,
	Router\Router
};

Class RegistrationView extends AbstractView
{
	public static function new(array $varPage): string
	{
		ob_start(); ?>
		<div class="connexion-container container">
			<h2><?=$varPage['h2'] ?? ''?></h2>
			<form method="post" id='form'>
				<label for="username">Login<input type="text" name="username" id="username" value="<?= htmlentities($varPage['recordedInputs']['username'] ?? '') ?>" required><?= $varPage['messages']['usernameSms'] ?? '' ?></label>
				<label for="password">Password<input type="password" name="password" id="password" required><?= $varPage['messages']['passwordSms'] ?? '' ?></label>
				<label for="pwdConfirm">Confirm Password<input type="password" name="pwdConfirm" id="pwdConfirm" required></label>
				<label for="email">email<input type="email" name="email" id="email" value="<?= htmlentities($varPage['recordedInputs']['email'] ?? '') ?>" required><?= $varPage['messages']['emailSms'] ?? '' ?></label>
				<input id="validation" class="btn" type="submit" value="INSCRIPTION">
				<a class="link" href="<?=Router::url('connection')?>">Vous avez déjà un compte ?</a>
			</form>
		</div>
		<?php return ob_get_clean();
	}
}