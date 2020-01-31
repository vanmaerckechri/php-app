<?php

namespace App\Mail;

use Core\ {
	Router\Router,
	AbstractMail
};

class RegistrationMail extends AbstractMail
{
	protected static function getHeader(): string
	{
		$header = "From: \"PHP-APP\"<php_app@cvm.com>\n";
		$header .= "Content-Type: text/html; charset=\"UTF-8\"\n";
		$header .= "Content-Transfer-Encoding: 8bit";

		return $header;
	}

	protected static function getSubject(): string
	{
		return 'Validation de Votre Compte';
	}

	protected static function getMessage(array $vars): string
	{
		$link = Router::url('registrationValidation', ['token' => $vars['token']]);
		ob_start();
		?>
		<div>
			<h1>PHP-APP</h1>
			<h2>Validation de Votre Compte</h2>
			<div>
				<p><?=$vars['token']?></p>
				<p>Bienvenue! Il ne vous reste plus qu'à cliquer sur le lien suivant pour activer votre compte.</p>
				<a href="<?=$link?>"><?=$link?></a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}
}