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

	public function disableFilterUnique(): self
	{
		$this->switchFilterUniqueStatus(false);
		return $this;
	}

	public function enableFilterUnique(): self
	{
		$this->switchFilterUniqueStatus(true);
		return $this;
	}

	private function switchFilterUniqueStatus(bool $status): void
	{
		foreach ($this->rules as $varName => $rules)
		{
			foreach ($rules as $ruleName => $value)
			{
				if ($ruleName === 'unique')
				{
					$this->rules[$varName][$ruleName]['status'] = $status;
				}
			}
		}
	}

	private function initFilterUnique(string $className, object $request): void
	{
		foreach ($this->rules as $varName => $rules)
		{
			foreach ($rules as $ruleName => $value)
			{
				if ($ruleName === 'unique' && $value === true)
				{
					$this->rules[$varName][$ruleName] = array(
						'status' => true,
						'class' => $className,
						'column' => $varName,
						'request' => $request
					);
				}
			}
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