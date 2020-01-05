<?php

namespace App\Controller;

use App\Authentification\Auth;

Class ViewManager
{
	protected $varPage = array();

	protected function loadPage(array $view, array $varPage): void
	{
		$class = "App\View\\$view[0]";
		$method = $view[1];
		$varPage['content'] = call_user_func_array([$class, $method], [$varPage]);
		call_user_func_array(['App\View\Template', 'load'], [$varPage]);
	}

	protected function redirectLoggedUser(string $redirection, string $role = 'whatever'): void
	{
		$user = Auth::user();
		if ($user && ($role === 'whatever' || $user->getRole() === $role))
		{
			header('Location: ' . DIRECTORY_SEPARATOR . $GLOBALS['router']->url($redirection));
			exit();
		}
	}
}