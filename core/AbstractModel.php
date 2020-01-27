<?php 

namespace Core;

abstract class AbstractModel
{
	public $classname;
	public $table;
	public $rules;

	public function __construct($class)
	{
		$this->classname = substr($class, strrpos($class, '\\') + 1);
		$this->table = strtolower($this->classname);
		$this->initValidationRules();
	}

	public function isValid(array $inputs, bool $setColumns = true): bool
	{
		$isValid = true;
		foreach ($inputs as $column => $value) 
		{
			if (Validator::isValid($this, $column, $value))
			{
				if ($setColumns === true)
				{
					$setCol = 'set' . ucfirst($column);
					if (is_string($value) || is_int($value))
					{
						$value = trim($value);
					}
					$this->$setCol($value);
				}
			}
			else
			{
				$isValid = false;
			}
		}
		return $isValid;
	}

	public function isUnique(array $columns, int $idToExclude = null): bool
	{
		$isValid = true;
		foreach ($columns as $column) 
		{
			$getCol = 'get' . ucfirst($column);
			$value = $this->$getCol();
			if (!Validator::isUnique($this, $column, $value, $idToExclude))
			{
				$isValid = false;
			}
		}
		return $isValid;
	}

	public function incrementAlreadyUsed($column): string
	{
		$repoClass = 'App\\Repository\\' . $this->classname . 'Repository';
		$getMethod = 'get' . ucfirst($column);
		$findMethod = 'find' . $this->classname . 'By' . ucfirst($column);

		$string = $this->$getMethod();
		$newString = $string;

		while (!is_null($string))
		{
			if (preg_match('#(\d+)$#', $newString, $matches, PREG_OFFSET_CAPTURE))
			{
			    $index = $matches[0][1];
			    $number = ++$matches[1][0];
			    $newString = substr_replace($newString, $number, $index);
			}
			else
			{
				$newString .= '1';
			}

			$string = $repoClass::$findMethod($newString);
		}

		return $newString;
	}

	public function getValuesToPush(bool $isCheckRequired = true): ?array
	{
		$output = array();
		foreach ($this->rules as $column => $rules)
		{
			$getVar = 'get' . ucfirst($column);
			foreach ($rules as $ruleId => $ruleValue)
			{
				$value = $this->$getVar();
				// save all columns that have a value, if one of the required columns has a null value, return null
				if ($isCheckRequired === true && $ruleId === 'required' && $ruleValue === true)
				{
					if (is_null($value))
					{
						return null;
					}
					else
					{
						$output[$column] = $value;
					}
				}
				else
				{
					if (!is_null($value))
					{
						$output[$column] = $value;
					}
				}
			}
		}
		return $output;
	}

	private function initValidationRules(): void
	{
		$schemaClass = 'App\\Schema\\' . $this->classname . 'Schema';
		$this->rules = $schemaClass::$schema;

		foreach ($this->rules as $column => $rules)
		{
			foreach ($rules as $ruleName => $value)
			{
				if ($ruleName === 'default' && $value === 'not null')
				{
					$this->rules[$column]['required'] = true;
				}
				else if ($ruleName === 'type' && $value === 'int')
				{
					if (isset($this->rules[$column]['minLength']))
					{
						$this->rules[$column]['minLength'] = 10 ** $this->rules[$column]['minLength'];
					}
					if (isset($this->rules[$column]['maxLength']))
					{
						$this->rules[$column]['maxLength'] = 10 ** $this->rules[$column]['maxLength'];	
					}
				}
			}
		}
	}

}