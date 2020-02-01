<?php

namespace Core;

use Core\ {
	App,
	Router\Router,
	Authentification\Auth
};

abstract class AbstractController
{
	protected function renderer(string $class, string $method): void
	{
		$namespace = App::getConfig('autoload')['namespace'];
		$class = $namespace . "View\\$class";
		$this->varPage['content'] = call_user_func_array([$class, $method], [$this->varPage]);
		call_user_func_array([$namespace . 'View\Template', 'load'], [$this->varPage]);
		exit;
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

	protected function redirect(string $route, ?array $params = []): void
	{
		if (is_null($params) || $this->processRedirectCondition($params) === true)
		{
			if (array_key_exists('code', $params))
			{
				http_response_code($params['code']);
			}
			if (array_key_exists('url', $params))
			{
				header('Location: ' . Router::url($route, $params['url']));
			}
			else
			{
				header('Location: ' . Router::url($route));
			}
			exit;
		}
	}

	private function processRedirectCondition(array $params): bool
	{
		$user = Auth::user();

		if (array_key_exists('logged', $params) && is_null($user) === $params['logged'])
		{
			return false;
		}		
		if ($user)
		{
			$role = $user->getRole();

			if (array_key_exists('minRole', $params) && $role < $params['minRole'])
			{
				return false;
			}
			if (array_key_exists('maxRole', $params) && $role > $params['maxRole'])
			{
				return false;
			}
		}
		return true;
	}
}