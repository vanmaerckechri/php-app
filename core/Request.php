<?php

namespace Core;

use PDO;

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

	public function count($column): self
	{
		$this->prepare = "SELECT COUNT($column)";
		return $this;		
	}

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

	public function orderBy(string $orderBy): self
	{
		$this->prepare .= " ORDER BY $orderBy";
		return $this;		
	}

	public function limit(int $size): self
	{
		$this->prepare .= " LIMIT $size";
		return $this;		
	}

	public function offset(int $size): self
	{
		$this->prepare .= " OFFSET $size";
		return $this;		
	}

	public function options(string $options): self
	{
		$this->prepare .= " $options";
		return $this;		
	}

	public function fetchClass(): ?object
	{
		$class = 'App\\Model\\' . ucfirst($this->table);
		$stmt = $this->execute();
		$result = $stmt->fetchObject($class);
		return $result ?: null;
	}

	public function fetchAllClass(): ?array
	{
		$class = 'App\\Model\\' . ucfirst($this->table);
		$stmt = $this->execute();
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, $class);
		return $result ?: null;		
	}

	public function fetchNum(): ?array
	{
		$class = 'App\\Model\\' . ucfirst($this->table);
		$stmt = $this->execute();
		$result = $stmt->fetch(PDO::FETCH_NUM);
		return $result ?: null;
	}

	private function execute()
	{
		$schemaClass = 'App\\Schema\\' . $this->table . 'Schema';
		$schema = $schemaClass::$schema;

		$stmt = Helper::getPdo()->prepare($this->prepare);
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
			case 'INT':
				return PDO::PARAM_INT;
			case 'BOOL':
				return PDO::PARAM_BOOL;
			case 'NULL':
				return PDO::PARAM_NULL;
			default:
				return PDO::PARAM_STR;
		}
	}
}