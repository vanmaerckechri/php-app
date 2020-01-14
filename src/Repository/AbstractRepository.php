<?php

namespace App\Repository;

use App\Request\Request;

abstract class AbstractRepository
{
	public static function findObjByCol(string $column, $value, string $options = ''): ?Object
	{
		$childClass = self::getChildClass();

		$request = new Request();
		$obj = $request
			->select('*')
			->from(strtolower($childClass))
			->where($column, $value)
			->options($options)
			->fetchClass();

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

	public static function record(object $obj): bool
	{
		// insert into table if all required columns are not null
		$inputs = $obj->getValuesToRecord();
		if ($inputs)
		{
			$request = new Request();
			$request->insertInto($obj->table)->values($inputs);
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