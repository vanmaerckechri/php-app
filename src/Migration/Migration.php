<?php

namespace App\Migration;

use PDO;

class Migration
{
	private $pdo;
	private $db = "CREATE DATABASE IF NOT EXISTS {{ dbname }} DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
	private $tables = ['user', 'article', 'category'];

	public function __construct()
	{
		$settings = require $_SERVER['DOCUMENT_ROOT'] . '/src/Config/db.php';

		// connect to server to create new db
		$this->pdo = new PDO('mysql:host=' . $settings['host'], $settings['user'], $settings['pwd']);
		// create db
		$this->createDb($settings['dbname']);
		// connect to db
		$this->pdo = new PDO("mysql:host=$settings[host]; dbname=$settings[dbname]", $settings['user'], $settings['pwd']);		
		// create table(s)
		foreach ($this->tables as $table)
		{
			$request = $this->buildSqlRequest($table);
			$this->createTable($request);
		}
	}

	private function buildSqlRequest(string $table): string
    {
    	$table = $this->getTableInfos($table);

        $request = "CREATE TABLE IF NOT EXISTS `$table[name]`(";
        $lines = '';
        $primKey = '';
        $uniques = array();

        // $table['schema'] is an array which will also be used for the input validator for models
        foreach ($table['schema'] as $column => $rules)
        {
            $line = "`$column` {{ type }}{{ maxLength }} {{ default }} {{ autoInc }}";

            foreach ($rules as $rule => $value)
            {
            	// email is only used with the model validator
            	if ($value === 'email')
               	{
                	$value = 'varchar';
                }
                // if current rule exist in $line replace {{ $rule }} by $value
            	if (strpos($line, "{{ $rule }}") !== false)
                {
                	$replace = $value;
                	// length is a special case, it requires parentheses
                	if ($rule === 'maxLength')
                	{
                		$replace = "($value)";
                	}
                    $line = str_replace("{{ $rule }}", $replace, $line);
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
            // remove unused dummies and add the line to the other lines
            $line = preg_replace('/{{(.*?)}}/', '', $line);
            $line = trim($line);
            $lines .= $line . ",";
        }
        // add primary and unique key
        $lines .= $primKey === '' ? '' : $primKey . ',';
        $lines .= implode(',', $uniques);
        $lines = trim($lines, ',');

        // add constraint (foreign key)
        $lines .= $table['constraint'] ? ',' . $table['constraint'] : '';
        $lines = trim($lines, ',');

        // close lines with options (engine, charset, ...)
        $request .= $lines . ")$table[options]";

        return $request;
    }

	private function createDb($db): void
	{
		$requete = str_replace("{{ dbname }}", "`$db`", $this->db);
		$this->pdo->prepare($requete)->execute();
	}

	private function createTable(string $sqlRequest): void
	{        
        $request = $sqlRequest;
        $this->pdo->prepare($request)->execute();
	}

	private function getTableInfos(string $table): array
	{
		$class = 'App\\Schema\\' . ucfirst($table) . 'Schema';

		return array(
			'name' => $table,
			'schema' => $schema = $class::$schema,
    		'constraint' => $constraint = $class::$constraint,
    		'options' => $options = $class::$options
    	);
	}
}