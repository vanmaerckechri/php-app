<?php

namespace App\Controller;

use App\Controller\RestClient;
use App\Authentification\Oauth;

class GoogleConnexionController
{
	public function check(): void
	{
		if (isset($_GET['code']))
		{
			$oauth = new Oauth();
			$oauth->login('google');
		}
		else
		{
			header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url('connexion'));
		}
	}
}