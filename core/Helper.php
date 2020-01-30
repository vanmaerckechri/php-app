<?php

namespace Core;

use PDO;
use Core\App;

class Helper
{
	public static function slugify(string $string): string
	{
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public static function getClass(string $classType, string $table): string
	{
		$directory = ucfirst($classType);
		$classType = $classType === 'entity' ? '' : $classType;
		$table = ucfirst($table);
		return App::getConfig('autoload')['namespace'] . $directory . '\\' . $table . $classType;
	}

	public static function getAppDirectory(): string
	{
		$appDir = App::getConfig('autoload')['directory'];
        return $_SERVER['DOCUMENT_ROOT'] . '/' . $appDir;		
	}
}