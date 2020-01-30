<?php

namespace Core;

use PDO;
use Core\App;

class Request
{
	private $table;
	private $prepare;
	private $binds = array();

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

	public function insertInto(string $table, array $binds): self
	{
		$this->table = $table;
		$this->prepare = 'INSERT INTO ' . $table;
		$this->binds = $binds;
		$this->prepare .= '(' . implode(', ', array_keys($binds)) . ') VALUES (' . ':' . implode(', :', array_keys($binds)) . ')';
		return $this;
	}

	public function update(string $table, array $binds): self
	{
		$this->table = $table;
		$this->prepare = 'UPDATE ' . $table . ' SET ';
		$this->binds = $binds;
		foreach ($binds as $column => $value)
		{
			$this->prepare .= $column . "=:" . $column . ", ";
		}
		$this->prepare = trim($this->prepare, ', ');
		return $this;
	}

	public function innerJoin(string $link): self
	{
		$this->prepare .= ' INNER JOIN ' . $link;
		return $this;
	}

	public function on(string $table): self
	{
		$this->prepare .= ' ON ' . $table;
		return $this;
	}

	public function where(string $column, string $operator, $value): self
	{
		return $this->addCondition($column, $operator, $value, 'WHERE');
	}

	public function and(string $column, string $operator, $value): self
	{
		return $this->addCondition($column, $operator, $value, 'AND');	
	}

	public function or(string $column, string $operator, $value): self
	{
		return $this->addCondition($column, $operator, $value, 'OR');
	}

	private function addCondition(string $column, string $operator, $value, string $condition): self
	{
		$uniqueBind = $this->makeUniqueBind();
		$this->binds[$column] = array('bind' => $uniqueBind, 'value' => $value);
		$this->prepare .= " $condition ";
		$this->prepare .= "$column $operator :$uniqueBind";
		return $this;	
	}

	public function count($column): self
	{
		$this->prepare = "SELECT COUNT($column)";
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
		$entity = App::getClass('entity', $this->table);
		$stmt = $this->execute();
		$result = $stmt->fetchObject($entity);
		return $result ?: null;
	}

	public function fetchAllClass(): ?array
	{
		$entity = App::getClass('entity', $this->table);
		$stmt = $this->execute();
		$result = $stmt->fetchAll(PDO::FETCH_CLASS, $entity);
		return $result ?: null;		
	}

	public function fetchNum(): ?array
	{
		$stmt = $this->execute();
		$result = $stmt->fetch(PDO::FETCH_NUM);
		return $result ?: null;
	}

	public function execute()
	{
		$stmt = App::getPdo()->prepare($this->prepare);
		foreach ($this->binds as $column => $value)
		{
			$column = explode('.', $column);
			$schema = count($column) > 1 ? $this->getSchema($column[0]) : $this->getSchema($this->table);
			$column = array_pop($column);
			$param = $this->getBindParam($schema[$column]['type']);
			// array $value for binds from condition (with unique bind name)
			if (is_array($value))
			{
				$input = $value['value'];
				$bind = ':' . $value['bind'];
			}
			else
			{
				$input = $value;
				$bind = ':' . $column;
			}
			$stmt->bindValue($bind, $input, $param);
		}
		$stmt->execute();
		return $stmt;
	}

	private function getSchema(string $table)
	{
		$schemaClass = App::getClass('schema', $this->table);
		return $schemaClass::$schema;
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

	private function makeUniqueBind()
	{
		$chars = 'bcdfghjklmnpqrstvwxzaeiouy';
		$newBind = '';
		while (strlen($newBind) < 8)
		{
			$rand = rand(0, strlen($chars) - 1);
			$newBind .= $chars[$rand];
			if (array_search($newBind, $this->binds))
			{
				$newBind = '';
			}
		}
		return $newBind;
	}
}