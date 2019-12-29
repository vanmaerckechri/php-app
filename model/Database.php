<?php

namespace Model;
use \PDO;

class Database extends PDO
{
	private const DB_HOST = 'localhost';
	private const DB_NAME = 'train_php';
	private const DB_CHARSET = 'utf8';
	private const DB_USER = 'root';
	private const DB_PASS = '';

	public function __construct()
	{
		parent::__construct('mysql:host=' . self::DB_HOST . ';dbname=' . self::DB_NAME . '; charset=' . self::DB_CHARSET, self::DB_USER, self::DB_PASS, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);
	}

	public function read($sql, $binds)
	{
		try
		{
			$stmt = $this->prepare($sql);
			
			foreach ($binds as $key => $value)
			{
				$stmt->bindValue("$key", $value, PDO::PARAM_INT);
			}

			$stmt->execute();

			return $stmt->fetchAll();

		}
		catch(PDOException $e)
		{
			var_dump($e);
		}


	}

	public function create($sql, $binds)
	{
		try
		{
			$stmt = $this->prepare($sql);
			
			foreach ($binds as $key => $value)
			{
				$stmt->bindValue("$key", $value);
			}

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			var_dump($e);
		}
	}

	public function update($sql, $binds)
	{
		try
		{
			$stmt = $this->prepare($sql);
			
			foreach ($binds as $key => $value)
			{
				$stmt->bindValue("$key", $value);
			}

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			var_dump($e);
		}
	}

	public function delete($sql, $binds)
	{
		try
		{
			$stmt = $this->prepare($sql);
			
			foreach ($binds as $key => $value)
			{
				$stmt->bindValue("$key", $value);
			}

			$stmt->execute();
		}
		catch(PDOException $e)
		{
			var_dump($e);
		}
	}
}