<?php

namespace App\Request;

use PDO;
use App\App;

trait Request
{
	private function getBindParam(string $type): string
	{
		if ($type === "STR")
		{
			return PDO::PARAM_STR;
		}
		else if ($type === "INT")
		{
			return PDO::PARAM_INT;			
		}
		else if ($type === "BOOL")
		{
			return PDO::PARAM_BOOL;			
		}
		else
		{
			return PDO::PARAM_NULL;			
		}
	}
	
	protected function select(string $prepare, array $binds): ?object
	{
		$stmt = App::getPdo()->prepare($prepare);
		foreach ($binds as $column => $input)
		{
			$value = $input[0];
			$type = strtoupper($input[1]);
			$bind = ':'. $column;

			$param = $this->getBindParam($type);

			$stmt->bindValue($bind, $value, $param);
		}
		$stmt->execute();
		return $stmt;
	}

	protected function insert(string $prepare, array $binds)
	{
		$stmt = App::getPdo()->prepare($prepare);
		foreach ($binds as $column => $input)
		{
			$value = $input[0];
			$type = strtoupper($input[1]);
			$bind = ':'. $column;

			$param = $this->getBindParam($type);

			$stmt->bindValue($bind, $value, $param);
		}
		$stmt->execute();
	}
}