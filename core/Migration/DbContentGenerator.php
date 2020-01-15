<?php

namespace Core\Migration;

use PDO;
use Core\Helper;

/*
	DbContentGenerator::launch([
		'user' => ['iteration' => 5, 'forceRand' => ['created_at']],
		'category' => ['iteration' => 3],
		'article' => ['iteration' => 30, 'forceRand' => ['created_at']]
	]);
*/
class DbContentGenerator
{
	private static $faker;

	public static function launch(array $tables): void
	{
		$pdo = Helper::getPdo();

		self::deleteRows($pdo, $tables);
		self::loadFakerContent();
		self::hydrate($pdo, $tables);
	}

	private static function deleteRows(PDO $pdo, array $tables): void
	{
		$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');

		foreach ($tables as $table => $params)
		{
			$pdo->exec("TRUNCATE TABLE $table");
		}

		$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
	}

	private static function hydrate(PDO $pdo, array $tables)
	{
		foreach ($tables as $table => $params)
		{
			// get schema/rules of columns
			$class = 'App\\Schema\\' . ucfirst($table) . 'Schema';
			$schema = $class::$schema;

			for ($i = 0; $i < $params['iteration']; $i++)
			{
				$generation = array();

				foreach ($schema as $column => $rules)
				{
					$isForceRand = array_key_exists('forceRand', $params) && array_search($column, $params['forceRand']) !== false ? true : false;
					$isAutoInc = isset($rules['autoInc']) && $rules['autoInc'] === 'AUTO_INCREMENT';
					$isRequired = isset($rules['default']) && $rules['default'] === 'NOT NULL';

					if ($isForceRand || (!$isAutoInc && $isRequired))
					{
						$iteration = $tables[$table];

						// generate only column who are not an id link between 2 tables
						if (strpos($column, '_id') === false)
						{
							if (isset($rules['slug']))
							{
								$generation[$column] = Helper::slugify($generation[$rules['slug']]);
							}
							else
							{
								$min = isset($rules['minLength']) ?: 0;
								switch ($rules['type']) 
								{
									case 'int': $generation[$column] = self::generateInteger($min, $rules['maxLength']); break;
									case 'datetime': $generation[$column] = self::generateDatetime(); break;
									case 'email': $generation[$column] = self::generateEmail($min); break;
									default: $generation[$column] = self::generateString($min, $rules['maxLength'], $column); break;
								}
							}
						}
						else
						{
							$linkedTable = str_replace('_id', '', $column);
							$generation[$column] = self::getRandId($linkedTable);
						}
						$generation[$column] = addslashes($generation[$column]);
					}
				}
				$output = '';
				foreach ($generation as $col => $value)
				{
					$output .= "$col = '$value', ";
				}
				$output = trim($output, ', ');
				$pdo->exec("INSERT INTO $table SET $output");
			}
		}
	}

	private static function generateInteger(int $min, int $max): int
	{
		$min = pow(10, $min);
		$max = pow(10, $max);
		return random_int($min, $max);
	}

	private static function generateDatetime(): string
	{
		$timestamp = mt_rand(1, time());
		return date("Y-m-d H:i:s", $timestamp);
	}

	private static function generateEmail(int $min, int $max = 24): string
	{
		$output = '';
		$max -= 5;
		$length = rand($min, $max);
		$characters = 'abcdefghijklmnopqrstuvwxyz01234abcdefghijklmnopqrstuvwxyz56789abcdefghijklmnopqrstuvwxyz';
		$charLength = strlen($characters) - 1;
		for ($i = 0; $i < $length; $i++)
		{
			$index = rand(0, $charLength);
			$output .= $characters[$index];
		}
		$atPos = rand(2, intdiv(strlen($output), 2));
		$output = substr_replace($output, '@', $atPos, 0) . '.com';
		return $output;
	}

	private static function generateString(int $minLength, int $maxLength, string $column): ?string
	{
		$length = rand($minLength, $maxLength);
		$maxPosition = mb_strlen(self::$faker) - $length;
		$startPosition = rand(0, $maxPosition);
		$result = mb_substr(self::$faker, $startPosition, $length);
		return $result;
	}


	private static function getRandId(string $table): ?int
	{
		$class = 'App\\Repository\\' . ucfirst($table) . 'Repository';
		$ids = $class::FindAll();
		if (!is_null($ids))
		{
			$index = rand(0, count($ids) - 1);
			return $ids[$index]->getId();			
		}
		return null;
	}

	private static function loadFakerContent(): void
	{
		$path = $_SERVER['DOCUMENT_ROOT'] . "/src/Config/faker.txt";
		self::$faker = file_get_contents($path);
	}
	
}