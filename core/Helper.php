<?php

namespace Core;

use PDO;

class Helper
{
	private static $pdo;

	public static function getPdo(): ?PDO
	{
		if (!self::$pdo)
		{
			$dbServer = require $_SERVER['DOCUMENT_ROOT'] . '/src/Config/dbServer.php';
			self::$pdo = new PDO("mysql:host={$dbServer['host']}; dbname={$dbServer['db']['name']}; charset={$dbServer['charset']}", $dbServer['user'], $dbServer['pwd'], [
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]);
		}

		return self::$pdo;
	}

	public static function startSession(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
	}

	public static function slugify($string)
	{
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public static function getTableInfos(string $table): array
	{
		$class = 'App\\Schema\\' . ucfirst($table) . 'Schema';

		return array(
            'name' => $table,
			'schema' => $schema = $class::$schema,
    		'options' => $options = $class::$options
    	);
	}

	public static function devMode()
	{
		try
		{
			Helper::getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(\PDOException $e)
		{
			
		}
	}
}