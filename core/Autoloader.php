<?php

Class Autoloader
{
	private static function autoload($className)
	{
		if (strpos($className, 'App\\') === 0)
		{
			$className = str_replace('App\\', 'src' . DIRECTORY_SEPARATOR, $className);
		}
		$file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . $className . '.php';
		$file = str_replace('/', DIRECTORY_SEPARATOR, $file);

		if (file_exists($file))
		{
			include_once $file;
		}
	}

	public static function register()
	{
		spl_autoload_register(array(__CLASS__, 'autoload'));
	}
}

Autoloader::register();