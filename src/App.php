<?php

namespace App;

use PDO;

class App
{
	private static $pdo;

	public static function getPdo(): PDO
	{
		if (!self::$pdo)
		{
			$settings = require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/db.php';
			self::$pdo = new PDO("mysql:host=$settings[host]; dbname=$settings[dbname]; charset=$settings[charset]", $settings['user'], $settings['pwd'], [
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
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

	public static function recordInputs(array $inputs): void
	{
		$result = array();
		foreach ($inputs as $key => $value)
		{
			$result[$key] = htmlspecialchars($value);
		}
		$_SESSION['recordedInputs'] = $result;
	}

	public static function getRecordedInputs(): ?array
	{
		$result = isset($_SESSION['recordedInputs']) ? $_SESSION['recordedInputs'] : null;
		$_SESSION['recordedInputs'] = [];
		return $result;
	}

	public static function hydrateModel(object $model, array $inputs): ?object
	{
		foreach ($inputs as $k => $v) 
		{
			$var = ucfirst($k);
			$setVar = 'set' . $var;
			$model->$setVar($v);
			$getVar = 'get' . $var;
			if (!$model->$getVar())
			{
				return null;
			}
		}
		return $model;
	}
}