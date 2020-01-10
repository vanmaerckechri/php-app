<?php

namespace App\Request;

use PDO;
use App\App;

class Request
{
	private $table;
	private $prepare;
	private $binds = array();

	// INSERT INTO

	public function insertInto(string $table): self
	{
		$this->table = $table;
		$this->prepare = 'INSERT INTO ' . $table;
		return $this;
	}

	public function values(array $binds): self
	{
		$this->binds = $binds;
		$this->prepare .= '(' . implode(', ', array_keys($binds)) . ') VALUES (' . ':' . implode(', :', array_keys($binds)) . ')';
		$this->execute();
		return $this;
	}

	// SELECT

	public function select($columns): self
	{
		if (is_array($columns))
		{
			$columns = implode(', ', $columns);
		}
		$this->prepare = 'SELECT ' . $columns;
		return $this;
	}

	public function from(string $table): self
	{
		$this->table = $table;
		$this->prepare .= ' FROM ' . $table;
		return $this;
	}

	public function where(string $column, $value): self
	{
		$this->binds = array();
		$this->binds[$column] = $value;
		$this->prepare .= ' WHERE ';
		$this->prepare .= "$column = :$column";
		return $this;
	}

	public function and(string $column, $value): self
	{
		$this->binds[$column] = $value;
		$this->prepare .= ' AND ';
		$this->prepare .= "$column = :$column";
		return $this;		
	}

	public function or(string $column, $value): self
	{
		$this->binds[$column] = $value;
		$this->prepare .= ' OR ';
		$this->prepare .= "$column = :$column";
		return $this;		
	}

	public function fetchObject(): ?object
	{
		$class = 'App\\Model\\' . ucfirst($this->table);
		$stmt = $this->execute();
		$result = $stmt->fetchObject($class);
		return $result ?: null;
	}

	private function execute()
	{
		$schemaClass = 'App\\Schema\\' . $this->table . 'Schema';
		$schema = $schemaClass::$schema;

		$stmt = App::getPdo()->prepare($this->prepare);
		foreach ($this->binds as $column => $value)
		{
			$bind = ':'. $column;
			$param = $this->getBindParam($schema[$column]['type']);

			$stmt->bindValue($bind, $value, $param);
		}
		$stmt->execute();
		return $stmt;
	}

	private function getBindParam(string $type): string
	{
		$type = strtoupper($type);
		switch ($type)
		{
			case 'EMAIL':
			case 'VARCHAR':
			case 'TEXT':
			case 'DATETIME':
				return PDO::PARAM_STR;
			case 'INT':
				return PDO::PARAM_INT;
			case 'BOOL':
				return PDO::PARAM_BOOL;
			default:
				return PDO::PARAM_NULL;
		}
	}
}