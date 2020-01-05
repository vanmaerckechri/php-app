<?php

namespace App\Controller;

use App\Authentification\Oauth;

class GoogleConnexionController
{
	public function check(): void
	{
		$oauth = new Oauth();
		if (!$oauth->login('google'))
		{
			header('Location: ' . $GLOBALS['router']->url('connexion'));
		}
	}
}