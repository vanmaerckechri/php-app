<?php

namespace App\Validator;

use App\MessagesManager;

class Validator
{
	public static function checkRequired($data, bool $value): bool
	{
		if ($value === false)
		{
			return true;
		}

		return !empty($data);
	}

	public static function checkOnly($data, array $str): bol
	{
		foreach ($str as $value)
		{
			if ($value === $data)
			{
				return true;
			}
		}
		return false;
	}

	public static function checkType($data, string $type): bool
	{
		switch ($type)
		{
			case 'email':
				return filter_var($data, FILTER_VALIDATE_EMAIL);
			case 'int':
				return filter_var($data, FILTER_VALIDATE_INT);
			case 'string':
				return is_string($data);
			default:
				return false;
		}
	}

	public static function checkMinLength($data, int $length): bool
	{
		return strlen($data) >= $length;
	}

	public static function checkMaxLength($data, int $length): bool
	{
		return strlen($data) < $length;
	}

	public static function validate(string $outputId, $data, array $rules): bool
	{
		$errors = array($outputId => []);

		foreach ($rules as $k => $v)
		{
			if ($k === 'required')
			{
				if (!self::checkRequired($data, $v))
				{
					$errors[$outputId][$k] = null;
				}
			}
			if ($k === 'only')
			{
				if (!self::checkOnly($data, $v))
				{
					$errors[$outputId][$k] = implode(", ", $v);
				}
			}
			if ($k === 'type')
			{		
				$type = strtolower($v);
				if (!self::checkType($data, $v))
				{
					$errors[$outputId]['type_' . $type] = null;
				}
			}
			if ($k === 'minLength')
			{
				if (!self::checkMinLength($data, $v))
				{
					$errors[$outputId][$k] = $v;
				}
			}
			if ($k === 'maxLength')
			{
				if (!self::checkMaxLength($data, $v))
				{
					$errors[$outputId][$k] = $v;
				}
			}
		}

		if (!empty($errors[$outputId]))
		{
			MessagesManager::add($errors);
			return false;
		}
		return true;
	}
}