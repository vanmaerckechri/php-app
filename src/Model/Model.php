<?php 

namespace App\Model;

trait Model
{
	private $requiredVar = array();

	public function __construct()
	{
		//$this->recordRequiredVarName();
	}

	public function setMultiple(array $inputs): void
	{
		$invalidVar = array();
		foreach ($inputs as $k => $v) 
		{
			$var = ucfirst($k);
			$setVar = 'set' . $var;
			$this->$setVar($v);
		}
	}
/*
	private function isValid(string $value): ?string
	{
		$get = 'get' . ucfirst($value);
		if (is_null($this->$get()))
		{
			return $value;
		}
		return null;
	}

	public function isValidToRecord(): bool
	{
		return $this->isValid($this->requiredVar);
	}

	private function recordRequiredVarName(): void
	{
		foreach ($this->rules as $varName => $rules)
		{
			foreach ($rules as $ruleName => $value)
			{
				if ($ruleName === 'required' && $value === true)
				{
					$this->requiredVar[] = $varName;
				}
			}
		}
	}
*/
}