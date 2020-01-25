<?php

namespace Core\Router;

class Router
{
	private static $url;
	private static $routes = [];
	private static $namedRoutes = [];

	public static function init(): void
	{
		self::$url = $_GET['url'];
	}

	public static function get(string $path, string $callable, ?string $name = null): Route
	{
		return self::add($path, $callable, $name, 'GET');
	}

	public static function post(string $path, string $callable, ?string $name = null): Route
	{
		return self::add($path, $callable, $name, 'POST');
	}

	public static function delete(string $path, string $callable, ?string $name = null): Route
	{
		return self::add($path, $callable, $name, 'DELETE');
	}

	public static function put(string $path, string $callable, ?string $name = null): Route
	{
		return self::add($path, $callable, $name, 'PUT');
	}

	public static function run()
	{
		$method = $_POST['method'] ?? $_SERVER['REQUEST_METHOD'];
		$method = strtoupper($method);

		if(!isset(self::$routes[$method]))
		{
			throw new RouterException('REQUEST_METHOD does not exist');
		}

		foreach (self::$routes[$method] as $route)
		{
			if ($route->match(self::$url))
			{
				return $route->call();
			}
		}

		throw new RouterException('No matching routes');
	}

	public static function url(string $name, array $params = []): string
	{
		if (!isset(self::$namedRoutes[$name]))
		{
			throw new RouterException('No route matches this name');
		}

		$url = (!empty($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/' . self::$namedRoutes[$name]->getUrl($params);
		return $url;
	}

	public static function isRouteForUrl(string $routeName, string $url): bool
	{
		if (isset(self::$namedRoutes[$routeName]))
		{
			if (self::$namedRoutes[$routeName]->match($url))
			{
				return true;
			}
		}
		return false;
	}

	public static function getCurrentRouteName(): ?string
	{
		foreach (self::$namedRoutes as $name => $obj)
		{
			if (self::isRouteForUrl($name, self::$url))
			{
				return $name;
			}
		}
		return null;
	}

	private static function add(string $path, string $callable, ?string $name, string $method): Route
	{
		$route = new Route($path, $callable);
		self::$routes[$method][] = $route;
		if ($name)
		{
			self::$namedRoutes[$name] = $route;
		}
		return $route;
	}

}