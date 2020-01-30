<?php

namespace Core;

use PDO;
use Core\Helper;

class App
{
	private static $pdo;
	private static $config;

	public static function start(): void
	{
		self::startSession();
		self::loadConfig();
		self::loadPdo();
	}

	public static function getPdo(): ?PDO
	{
		return self::$pdo;
	}

	public static function getConfig(string $name = null)
	{
		return $name ? self::$config[$name] : self::$config;
	}

	public static function devMode()
	{
		try
		{
			self::getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(\PDOException $e)
		{
			
		}
	}

	private static function startSession(): void
	{
		if (session_status() === PHP_SESSION_NONE)
		{
			session_start();
		}
	}

	private static function loadConfig(): void
	{
		if (!self::$config)
		{
			$file = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'cvm_php_init.json';
			if (file_exists($file))
			{
				self::$config = json_decode(file_get_contents($file), true);
			}
		}
	}

	private static function loadPdo(): void
	{
		if (!self::$pdo)
		{
			$file = Helper::getAppDirectory() . 'Config/security.json';
			$server = json_decode(file_get_contents($file), true)['server'];
			self::$pdo = new PDO("mysql:host={$server['host']}; dbname={$server['db']['name']}; charset={$server['charset']}", $server['user'], $server['pwd'], [
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]);
		}
	}
}