<?php

namespace Core\Devboard;

use PDO;
use Core\ {
	App,
	Helper
};

/*
	FillDatabase::createRows([
		'tableName1' => ['iteration' => 5, 'forceRand' => ['columnName1', 'columnName2']],
		'tableName2' => ['iteration' => 3], ...
	]);
*/
class FillDatabase
{
	use TableInfos;

	public static function createRows(array $tables): void
	{
		$pdo = App::getPdo();

		self::deleteRows($pdo, $tables);
		self::hydrate($pdo, $tables);
	}

	public static function searchForeignKeyOnEmptyTable(string $table): ?string
	{	
        $tableInfos = TableInfos::get($table);
        foreach ($tableInfos['schema'] as $column => $rules)
        {
            if (isset($rules['foreignKey']['table']))
            {
            	$stmt = App::getPdo()->prepare("SELECT * FROM {$rules['foreignKey']['table']} LIMIT 1");
            	$stmt->execute();
            	if ($stmt->fetch() === false)
            	{
            		return $rules['foreignKey']['table'];
            	}
            }
        }
        return null;
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
			$schemaClass = Helper::getClass('schema', $table);
			$schema = $schemaClass::$schema;

			for ($i = 0; $i < $params['iteration']; $i++)
			{
				$generation = array();

				foreach ($schema as $column => $rules)
				{
					$isForceRand = array_key_exists('forceRand', $params) && array_search($column, $params['forceRand']) !== false ? true : false;
					$isRequired = isset($rules['default']) && $rules['default'] === 'not null';

					if ($isForceRand || $isRequired)
					{
						$iteration = $tables[$table];

						// generate only column who are not an id link between 2 tables
						if (!isset($rules['foreignKey']))
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
									case 'int':
										$generation[$column] = ContentGenerator::generateInteger($min, $rules['maxLength']);
										break;
									case 'datetime':
										$generation[$column] = ContentGenerator::generateDatetime();
										break;
									case 'email':
										$generation[$column] = ContentGenerator::generateEmail($min);
										break;
									case 'password':
										$generation[$column] = '$2y$10$Qqgas1Ik8rqG/u1ZmOQegO7BNR11AGNVXIReY4cqURc/cc19ST3d6';
										break;
									case 'text':
										$generation[$column] = ContentGenerator::generatePhrase($min, $rules['maxLength']);
										break;
									default:
										$generation[$column] = ContentGenerator::generatePhrase($min, $rules['maxLength'], false, false, false);
										break;
								}
							}
						}
						else
						{
							$generation[$column] = self::getRandRowValueByCol($rules['foreignKey']['table'], $rules['foreignKey']['column']);
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

	private static function getRandRowValueByCol(string $table, string $column): ?int
	{
		$stmt = App::getPdo()->prepare("SELECT * FROM $table");
		$stmt->execute();
		$result = $stmt->fetchAll();
		if ($result)
		{
			$index = rand(0, count($result) - 1);
			return $result[$index]['id'];		
		}
		return null;
	}
}