<?php

namespace App\Controller;

use App\MessagesManager;

class DisconnectionController
{
	public function check(): void
	{
		$_SESSION['auth'] = null;
		MessagesManager::add(['info' => ['disconnectComplete' => null]]);
		header('Location: ' . $GLOBALS['router']->url('connexion'));
		exit();
	}
}