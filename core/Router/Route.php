<?php

// https://www.grafikart.fr/tutoriels/router-628

namespace Core\Router;

use Core\App;

class Route
{
	private $path;
	private $callable;
	private $matches = [];
	private $params = [];

	public function __construct(string $path, string $callable)
	{
		$this->path = trim($path, '/');
		$this->callable = $callable;
	}

	public function with(string $param, string $regex): self
	{
		$this->params[$param] = str_replace('(', '(?:', $regex);
		return $this;
	}

	public function match(string $url): bool
	{
		$url = trim($url, '/');
		$path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
		$regex = "#^$path$#i";
		if (!preg_match($regex, $url, $matches))
		{
			return false;
		}
		array_shift($matches);
		$this->matches = $matches;
		return true;
	}

	private function paramMatch(array $match): string
	{
		if (isset($this->params[$match[1]]))
		{
			return '(' . $this->params[$match[1]] . ')';
		}
		return '([^/]+)';
	}

	public function call()
	{
		if (is_string($this->callable))
		{
			$params = explode('#', $this->callable);

			if ($params[0] === 'DevboardController')
			{
				$controller = 'Core\\Devboard\\' . $params[0];
			}
			else
			{
				$controller = App::getConfig('autoload')['namespace'] . 'Controller\\' . $params[0];
			}

			$controller = new $controller();

			return call_user_func_array([$controller, $params[1]], $this->matches);
		}
		else
		{
			return call_user_func_array($this->callable, $this->matches);
		}
	}

	public function getPath(): string
	{
		return $this->path;
	}

	public function getUrl(array $params): string
	{
		$path = $this->path;
		foreach ($params as $k => $v)
		{
			$path = str_replace(":$k", $v, $path);
		}
		return $path;
	}
}