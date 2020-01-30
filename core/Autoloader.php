<?php

namespace Autoloader;

Class Autoloader
{
	private static $namespace;
	private static $directory;

	public static function init():void
	{
		self::loadConfig();
		self::register();
	}

	public static function getNamespace(): array
	{
		return $namespace;
	}

	public static function getDirectory(): array
	{
		return $directory;
	}

	private static function loadConfig(): void
	{
		$file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cvm_php_init.json';
		if (file_exists($file))
		{
			$autoload = json_decode(file_get_contents($file), true)['autoload'];
			self::$namespace = $autoload['namespace'];
			self::$directory = $autoload['directory'];
		}
	}

	private static function register(): void
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}

	private static function autoload($className)
	{
		if (strpos($className, self::$namespace) === 0)
		{
			$className = str_replace(self::$namespace, self::$directory . DIRECTORY_SEPARATOR, $className);
		}
		$file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $className . '.php';
		$file = str_replace('/', DIRECTORY_SEPARATOR, $file);

		if (file_exists($file))
		{
			include_once $file;
		}
	}
}

Autoloader::init();