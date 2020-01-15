<?php

namespace Core\Migration;

use PDO;
use Core\Helper;

class Migration
{
    public function __construct()
    {
        $this->createDb();
    }

    public function createDb(): void
    {
        $dbServer = require $_SERVER['DOCUMENT_ROOT'] . '/src/Config/dbServer.php';
        $pdo = new PDO("mysql:host={$dbServer['host']}", $dbServer['user'], $dbServer['pwd']);
        $request = $this->mountDbRequest($dbServer['db']);
        $pdo->prepare($request)->execute();
    }

    public function createTable(string $table): void
    {   
        $request = $this->mountTableRequest($table);
        Helper::getPdo()->prepare($request)->execute();
    }

    public function createTables(array $tables): void
    {
        foreach ($tables as $table)
        {
            $this->createTable($table);
        }
    }

    private function mountDbRequest(array $db): string
    {
        return "CREATE DATABASE IF NOT EXISTS `{$db['name']}` DEFAULT CHARACTER SET {$db['default character']}";
    }

	private function mountTableRequest(string $table): string
    {
    	$table = $this->getTableInfos($table);
        $request = array(
            'column' => '',
            'primaryKey' => '',
            'unique' => array(),
            'foreignKey' => array(),
            'result' => "CREATE TABLE IF NOT EXISTS `{$table['name']}`(",
        );

        // $table['schema'] is an array which will also be used for the input validator for models
        foreach ($table['schema'] as $column => $rules)
        {
            $request = $this->mountTableColumns($request, $column, $rules);

            // remove unused markers and update result
            $request['column']  = preg_replace('/{{(.*?)}}/', '', $request['column']);
            $request['column']  = trim($request['column']);
            $request['result'] .= $request['column'] . ',';
        }

        // add primary key, unique key(s) and foreign key(s)
        $request['result'] .= $request['primaryKey'] ?? '';
        $request['result'] .= implode('', $request['unique']);
        $request['result'] .= implode('', $request['foreignKey']);

        // close request with options (engine, charset, ...)
        $options = $this->mountTableOptions($table['options']);

        return ($request['result'] . ')' . $options);
    }

	private function getTableInfos(string $table): array
	{
		$class = 'App\\Schema\\' . ucfirst($table) . 'Schema';

		return array(
            'name' => $table,
			'schema' => $schema = $class::$schema,
    		'options' => $options = $class::$options
    	);
	}

    private function mountTableColumns(array $request, string $column, array $rules): array
    {
        // markers that will help replace with values
        $request['column'] = "`$column` {{ type }}{{ maxLength }} {{ default }} {{ autoInc }}";

        foreach ($rules as $rule => $value)
        {
            // email is only used with the model validator
            if ($value === 'email')
            {
                $value = 'varchar';
            }
            // if current rule exist in dummies, replace {{ $rule }} by $value
            if (strpos($request['column'], "{{ $rule }}") !== false)
            {
                // length is a special case, it requires parentheses
                if ($rule === 'maxLength')
                {
                    $value = "($value)";
                }
                $request['column'] = str_replace("{{ $rule }}", $value, $request['column']);
            }
            else 
            {
                if ($rule === 'primaryKey' && $value === true)
                {
                    $request['primaryKey'] = "PRIMARY KEY (`$column`)";
                }
                else if ($rule === 'unique' && $value === true)
                {
                    $request['unique'][] = ", UNIQUE KEY `$column` (`$column`)";
                }
                else if ($rule === 'foreignKey')
                {
                    $params = $value;
                    if (isset($params['table']) && isset($params['column']))
                    {
                        $id = "{$params['table']}_{$params['column']}";

                        $constraint = isset($params['constraint']) && $params['constraint'] === true ? "CONSTRAINT `fk_{$id}`" : '';
                        $fk = "FOREIGN KEY (`{$id}`)";
                        $ref = "REFERENCES `{$params['table']}` (`{$params['column']}`)";

                        $request['foreignKey'][] = ", $constraint $fk $ref";
                    }
                }
            }
        }
        return $request;
    }

    private function mountTableOptions(array $options): string
    {
        $mounting = '';
        foreach ($options as $option => $value)
        {
            $mounting .= ' ' . strtoupper($option) . '=' . $value;
        }
        return $mounting;
    }
}