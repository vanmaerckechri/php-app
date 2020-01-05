<?php

namespace App\Controller;

use App\Controller\RestClient;
use App\Authentification\Oauth;

class GoogleConnexionController
{
	public function check(): void
	{
		$oauth = new Oauth();
		if (!$oauth->login('google'))
		{
			header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
		}
	}
}