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
		$view = $namespace . "View\\$class";

		if (isset($this->varPage['css']))
		{
			$this->varPage['css'] = self::addAssets('css', $this->varPage['css']);
		}
		if (isset($this->varPage['js']))
		{
			$this->varPage['js'] = self::addAssets('js', $this->varPage['js']);
		}
		$this->varPage['content'] = call_user_func_array([$view, $method], [$this->varPage]);
		call_user_func_array([$namespace . 'View\Template', 'display'], [$this->varPage]);
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

	private static function addAssets(string $type, array $list): ?string
	{
		$longType = $type === 'js' ? 'javascript' : 'css';
		ob_start();
		foreach ($list as $fileName) 
		{
			$path = "/public/{$type}/" . $fileName . ".{$type}";
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
			
			?><script type="text/<?=$longType?>" src="<?=$path?>"></script><?php
		}
		return ob_get_clean();
	}
}