<?php

namespace Core;

use PDO;

class Helper
{
	private static $pdo;

	public static function getPdo(): PDO
	{
		if (!self::$pdo)
		{
			$dbServer = require $_SERVER['DOCUMENT_ROOT'] . '/src/Config/dbServer.php';
			self::$pdo = new PDO("mysql:host={$dbServer['host']}; dbname={$dbServer['db']['name']}; charset={$dbServer['charset']}", $dbServer['user'], $dbServer['pwd'], [
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
			$result[$key] = $value;
		}
		$_SESSION['recordedInputs'] = $result;
	}

	public static function getRecordedInputs(): ?array
	{
		$result = isset($_SESSION['recordedInputs']) ? $_SESSION['recordedInputs'] : null;
		$_SESSION['recordedInputs'] = [];
		return $result;
	}

	public static function convertNamespaceToClassname(string $classname): string
	{
		return substr($classname, strrpos($classname, '\\') + 1);
	}

	public static function excerpt(string $content, int $limit = 60): string
	{
		if (mb_strlen($content) > $limit)
		{
			$lastSpacePos = mb_strpos($content, ' ', $limit);
			if (!is_int($lastSpacePos))
			{
				$lastSpacePos = $limit;
			}
			return mb_substr($content, 0, $lastSpacePos) . '...';
		}
		return $content;
	}

	public static function slugify($string)
	{
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}

	public static function getTablesFromSchemas()
	{
		$files =  array_values(array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . '/src/Schema/'), ['..', '.']));
		return array_map('strtolower', preg_replace('/Schema.php/', '', $files));
	}
}