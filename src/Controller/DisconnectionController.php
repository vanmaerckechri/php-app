<?php

namespace App\Controller;

use Core\{
	MessagesManager,
	Router\Router
};

class DisconnectionController
{
	public function check(): void
	{
		$_SESSION['auth'] = null;
		MessagesManager::add(['info' => ['disconnectComplete' => null]]);
		header('Location: ' . Router::url('connexion'));
		exit();
	}
}