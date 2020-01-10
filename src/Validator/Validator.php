<?php

namespace App\Validator;

use App\App;
use App\MessagesManager;

class Validator
{
	public static function isUnique(object $obj, string $column, $input): bool
	{
		$repoClass = 'App\\Repository\\' . $obj->classname . 'Repository';
		$errors = array();

		$smsId = $column . 'Sms';
		$findMethod = 'find' . $obj->classname . 'By' . ucfirst($column);

		if ($repoClass::$findMethod($input))
		{
			$errors[$smsId][$column . 'Taken'] = null;				
		}

		if (!empty($errors[$smsId]))
		{
			MessagesManager::add($errors);
			return false;
		}
		return true;
	}

	public static function isValid(object $obj, string $column, $input): bool
	{
		$errors = array();
		$smsId = $column . 'Sms';
		$errors[$smsId] = array();
		$rules = $obj->rules[$column];

		foreach ($rules as $rule => $value)
		{
			if ($rule === 'required')
			{
				if (!self::checkRequired($input, $value))
				{
					$errors[$smsId][$rule] = null;
				}
			}
			if ($rule === 'only')
			{
				if (!self::checkOnly($input, $value))
				{
					$errors[$smsId][$rule] = implode(", ", $rule);
				}
			}
			if ($rule === 'type')
			{		
				$type = strtolower($rule);
				if (!self::checkType($input, $value))
				{
					$errors[$smsId]['type_' . $value] = null;
				}
			}
			if ($rule === 'minLength')
			{
				if (!self::checkMinLength($input, $value))
				{
					$errors[$smsId][$rule] = $value;
				}
			}
			if ($rule === 'maxLength')
			{
				if (!self::checkMaxLength($input, $value))
				{
					$errors[$smsId][$rule] = $value;
				}
			}
		}

		if (!empty($errors[$smsId]))
		{
			MessagesManager::add($errors);
			return false;
		}
		return true;
	}

	private static function checkRequired($input, bool $value): bool
	{
		if ($value === false)
		{
			return true;
		}

		return !empty($input);
	}

	private static function checkOnly($input, array $str): bol
	{
		foreach ($str as $value)
		{
			if ($value === $input)
			{
				return true;
			}
		}
		return false;
	}

	private static function checkType($input, string $type): bool
	{
		switch ($type)
		{
			case 'email':
				return filter_var($input, FILTER_VALIDATE_EMAIL);
			case 'int':
				return filter_var($input, FILTER_VALIDATE_INT);
			case 'varchar':
			case 'text':
			case 'datetime':
				return is_string($input);
			default:
				return false;
		}
	}

	private static function checkMinLength($input, int $length): bool
	{
		return strlen($input) >= $length;
	}

	private static function checkMaxLength($input, int $length): bool
	{
		return strlen($input) < $length;
	}
}