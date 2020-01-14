<?php

namespace App\Controller;

use App\Authentification\Auth;

abstract class AbstractController
{
	protected $varPage = array();

	protected function renderer(array $view): void
	{
		$class = "App\View\\$view[0]";
		$method = $view[1];
		$this->varPage['content'] = call_user_func_array([$class, $method], [$this->varPage]);
		call_user_func_array(['App\View\Template', 'load'], [$this->varPage]);
	}

	protected function redirectLoggedUser(string $redirection, string $role = 'whatever'): void
	{
		$user = Auth::user();
		if ($user && ($role === 'whatever' || $user->getRole() === $role))
		{
			header('Location: ' . $GLOBALS['router']->url($redirection));
			exit();
		}
	}
}