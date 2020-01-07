<?php 

namespace App\Model;

trait Model
{
	protected $rules;

	public function isValidToSelect(array $inputs): bool
	{
		$this->setMultiple($inputs, false);

		if ($this->isReadyToSelect($inputs) === true)
		{
			return true;
		}
		return false;
	}

	public function isValidToInsert(array $inputs): bool
	{
		$this->setMultiple($inputs, true);

		if ($this->isReadyToInsert() === true)
		{
			return true;
		}
		return false;
	}

	protected function initValidationRules(string $className, object $request, array $schema): void
	{
		$this->rules = $schema;

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
				if ($ruleName === 'default' && $value === 'NOT NULL' && (!isset($rules['autoInc']) || $rules['autoInc'] !== 'AUTO_INCREMENT' ))
				{
					$this->rules[$varName]['required'] = true;
				}

			}
		}
	}

	private function setMultiple(array $inputs, bool $isTestUniqueFilter): void
	{
		$invalidVar = array();
		foreach ($inputs as $k => $v) 
		{
			$var = ucfirst($k);
			$setVar = 'set' . $var;
			if (isset($this->rules[$k]['unique']['status']))
			{
				$this->rules[$k]['unique']['status'] = $isTestUniqueFilter;
			}
			$this->$setVar($v);
		}
	}

	private function isReadyToSelect(array $inputs): bool
	{
		foreach ($inputs as $varName => $value)
		{
			if ($this->$varName === null)
			{
				return false;
			}
		}
		return true;
	}

	private function isReadyToInsert(): bool
	{
		foreach ($this->rules as $varName => $rules)
		{
			foreach ($rules as $ruleName => $value)
			{
				if ($ruleName === 'required' && $value === true)
				{
					if ($this->$varName === null)
					{
						return false;
					}
				}
			}
		}
		return true;
	}
}