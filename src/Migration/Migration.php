<?php

namespace App\Migration;

use PDO;

class Migration
{
	private $pdo;
	private $tables = ['user'];

	public function __construct()
	{
		$settings = require $_SERVER['DOCUMENT_ROOT'] . '/src/Config/db.php';

		// $settings['dbname'] | temp --->
		$dbname = 'test';
		// <---

		$this->pdo = new PDO('mysql:host=' . $settings['host'], $settings['user'], $settings['pwd']);

		$this->createDb($dbname);
		$this->pdo = new PDO("mysql:host=$settings[host]; dbname=$dbname", $settings['user'], $settings['pwd']);		

		foreach ($this->tables as $table)
		{
			$class = 'App\\Schema\\' . ucfirst($table) . 'Schema';
			$request = $this->buildSqlRequest($table, $class::getSchema());
			$this->createTable($request);
		}
	}

	private function buildSqlRequest(string $table, array $schema): string
    {
        $request = "CREATE TABLE IF NOT EXISTS `$table`(";
        $lines = '';
        $primKey = '';
        $uniques = array();

        foreach ($schema as $column => $rules)
        {
            $line = '{{ column }} {{ type }}({{ maxLength }}) {{ default }} {{ autoInc }}';
            $line = str_replace("{{ column }}", "`$column`", $line);
            foreach ($rules as $rule => $value)
            {
            	if ($value === 'email')
               	{
                	$value = 'varchar';
                }
            	if (strpos($line, "{{ $rule }}") !== false)
                {
                    $line = str_replace("{{ $rule }}", $value, $line);
                }
                else 
                {
                	if ($rule === 'primaryKey' && $value === true)
	                {
	                    $primKey = "PRIMARY KEY (`$column`)";
	                }
	                else if ($rule === 'unique' && $value === true && (!isset($rules['primKey']) || $rules['primKey'] === false))
	                {
	                    $uniques[] = "UNIQUE KEY `$column` (`$column`)";
	                }
	            }
            }
            $line = preg_replace('/{{.*}}/', '', $line);
            $line = trim($line);
            $lines .= $line . ",";
        }
        $lines .= $primKey === '' ? '' : $primKey . ',';
        $lines .= implode(',', $uniques);
        $lines = trim($lines, ',');
        $request .= $lines . ")ENGINE=InnoDB AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;";

        return $request;
    }

	private function createDb(string $dbName): void
	{
		$requete = "CREATE DATABASE IF NOT EXISTS `".$dbName."` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
		$this->pdo->prepare($requete)->execute();
	}

	private function createTable(string $sqlRequest): void
	{        
        $request = $sqlRequest;
        $this->pdo->prepare($request)->execute();
	}
}