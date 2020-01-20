<?php

namespace Core;

use Core\Router\Router;
use Core\Authentification\Auth;

abstract class AbstractController
{
	protected $varPage = array();

	protected function renderer(string $class, string $method): void
	{
		$class = "App\View\\$class";
		$this->varPage['content'] = call_user_func_array([$class, $method], [$this->varPage]);
		call_user_func_array(['App\View\Template', 'load'], [$this->varPage]);
	}

	protected function recordInputs(array $inputs): void
	{
		$result = array();
		foreach ($inputs as $key => $value)
		{
			$result[$key] = $value;
		}
		$_SESSION['recordedInputs'] = $result;
	}

	protected function getRecordedInputs(): ?array
	{
		$result = isset($_SESSION['recordedInputs']) ? $_SESSION['recordedInputs'] : null;
		$_SESSION['recordedInputs'] = [];
		return $result;
	}

	protected function redirect(string $route, ?array $params = null): void
	{
		if (is_null($params) || $this->processRedirectCondition($params))
		{
			header('Location: ' . Router::url($route));
			exit();
		}
	}

	private function processRedirectCondition(array $params): bool
	{
		$user = Auth::user();
		if (array_key_exists('logged', $params))
		{
			if (is_null($user) === $params['logged'])
			{
				return false;
			}
		}		
		if ($user)
		{
			$role = $user->getRole();

			if (array_key_exists('minRole', $params))
			{
				if ($role < $params['minRole'])
				{
					return false;
				}
			}
			if (array_key_exists('maxRole', $params))
			{
				if ($role > $params['maxRole'])
				{
					return false;
				}
			}
		}
		return true;
	}
}