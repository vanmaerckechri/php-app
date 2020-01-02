<?php

namespace App\Validator;

use App\ErrorsManager;

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

	public static function checkType($data, string $type): bool
	{
		$checkFunction = "is_$type";
		return $checkFunction($data);
	}

	public static function checkMinLength($data, int $length): bool
	{
		return strlen($data) > $length;
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
			if ($k === 'type')
			{		
				if (!self::checkType($data, $v))
				{
					$errors[$outputId][$k] = $v;
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
			ErrorsManager::add($errors);
			return false;
		}
		return true;
	}
}