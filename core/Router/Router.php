<?php

namespace Core\Router;

class Router
{
	private $url;
	private $routes = [];
	private $namedRoutes = [];

	public function __construct($url)
	{
		$this->url = $url;
	}

	private function add($path, $callable, $name, $method)
	{
		$route = new Route($path, $callable);
		$this->routes[$method][] = $route;
		if ($name)
		{
			$this->namedRoutes[$name] = $route;
		}
		return $route;		
	}

	public function get($path, $callable, $name = null)
	{
		return $this->add($path, $callable, $name, 'GET');
	}

	public function post($path, $callable, $name = null)
	{
		return $this->add($path, $callable, $name, 'POST');
	}

	public function delete($path, $callable, $name = null)
	{
		return $this->add($path, $callable, $name, 'DELETE');
	}

	public function put($path, $callable, $name = null)
	{
		return $this->add($path, $callable, $name, 'PUT');
	}

	public function run()
	{
		$method = $_POST['method'] ?? $_SERVER['REQUEST_METHOD'];
		$method = strtoupper($method);

		if(!isset($this->routes[$method]))
		{
			throw new RouterException('REQUEST_METHOD does not exist');
		}

		foreach ($this->routes[$method] as $route)
		{
			if ($route->match($this->url))
			{
				return $route->call();
			}
		}

		throw new RouterException('No matching routes');
	}

	public function url($name, $params = [])
	{
		if (!isset($this->namedRoutes[$name]))
		{
			throw new RouterException('No route matches this name');
		}

		$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . $this->namedRoutes[$name]->getUrl($params);
		return $url;
	}
}