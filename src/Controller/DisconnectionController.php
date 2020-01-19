<?php

namespace App\Controller;

use Core\{
	Router\Router,
	MessagesManager\MessagesManager
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