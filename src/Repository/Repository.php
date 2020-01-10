<?php

namespace App\Repository;

use App\Request\Request;

class Repository
{
	public static function findObjByCol(string $column, $value): ?Object
	{
		$childClass = get_called_class();
		$childClass = substr($childClass, strrpos($childClass, '\\') + 1);
		$childClass = str_replace('Repository', '', $childClass);

		$request = new Request();
		$obj = $request
			->select('*')
			->from(strtolower($childClass))
			->where($column, $value)
			->fetchObject();

		return $obj ?: null;
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
}