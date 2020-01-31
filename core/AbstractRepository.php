<?php

namespace Core;

abstract class AbstractRepository
{
	public static function findOneByCol(string $column, $value): ?Object
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$obj = $request
			->select('*')
			->from(strtolower($childClass))
			->where($column, '=', $value)
			->limit(1)
			->fetchClass();

		return $obj ?: null;
	}

	public static function findUnique(string $column, $value, ?int $idToExclude): ?Object
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$obj = $request
			->select('*')
			->from(strtolower($childClass))
			->where($column, '=', $value);

		if ($idToExclude !== null)
		{
			$obj->and('id', '!=', $idToExclude);
		}

		$obj = $obj->fetchClass();

		return $obj ?: null;
	}

	public static function findAll(string $options = ''): ?array
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$output = $request
			->select('*')
			->from(strtolower($childClass))
			->options($options)
			->fetchAllClass();

		return $output ?: null;
	}

	public static function findAllLimitOffset(string $select, string $orderBy, string $limit, string $offset): ?array
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$output = $request
			->select($select)
			->from(strtolower($childClass))
			->orderBy($orderBy)
			->limit($limit)
			->offset($offset)
			->fetchAllClass();

		return $output ?: null;
	}

	public static function findNextEarler($select, int $id, string $createdAt, string $createdColName = 'created_at'): ?Object
	{
		return self::findNextEarlerOrLater($select, $id, $createdAt, $createdColName, '<=', 'DESC');
	}

	public static function findNextLater($select, int $id, string $createdAt, string $createdColName = 'created_at'): ?Object
	{
		return self::findNextEarlerOrLater($select, $id, $createdAt, $createdColName, '>=', 'ASC');
	}

	private static function findNextEarlerOrLater($select, int $id, string $createdAt, string $createdColName, string $operator, string $direction): ?Object
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$output = $request
			->select($select)
			->from(strtolower($childClass))
			->where($createdColName, $operator, $createdAt)
			->and('id', '!=', $id)
			->orderBy("$createdColName $direction")
			->limit(1)
			->fetchClass();
		return $output ?: null;
	}

	public static function countRowByCol(string $column): int
	{
		$childClass = self::getChildClass();
		$request = new Request();
		$obj = $request
			->count($column)
			->from(strtolower($childClass))
			->fetchNum();
		return intval($obj[0]);
	}

	public static function record(object $obj): bool
	{
		// insert into table if all required columns are not null
		$inputs = $obj->getValuesToPush();
		if ($inputs)
		{
			$request = new Request();
			$request->insertInto($obj->table, $inputs)->execute();
			return true;
		}
		return false;
	}

	public static function updateById(object $obj, int $id): bool
	{
		$inputs = $obj->getValuesToPush(false);
		if ($inputs)
		{
			var_dump($inputs);
			$request = new Request();
			$request->update($obj->table, $inputs)->where('id', '=', $id)->execute();
			return true;
		}
		return false;
	}

	private static function getChildClass()
	{
		$childClass = get_called_class();
		$childClass = substr($childClass, strrpos($childClass, '\\') + 1);
		return str_replace('Repository', '', $childClass);		
	}
}