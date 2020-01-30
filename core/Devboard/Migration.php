<?php

namespace Core\Devboard;

use PDO;
use Core\ {
    App,
    Helper
};

class Migration
{
    use TableInfos;
    
    private $server;
    private $connectServ;

    public function __construct()
    {
        $file = Helper::getAppDirectory() . 'Config/security.json';
        $this->server = json_decode(file_get_contents($file), true)['server'];
        $this->connectServ = new PDO("mysql:host={$this->server['host']}", $this->server['user'], $this->server['pwd']);
    }

    public function getDbName(): ?string
    {
        return $this->server['db']['name'];
    }

    public function checkDbExist(): bool
    {
        $stmt = $this->connectServ->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$this->server['db']['name']}'");
        $stmt->execute();
        if ($stmt->fetch() !== false)
        {
            return true;
        }
        return false;
    }

    public function createDatabase(): void
    {
        $request = $this->mountDbRequest($this->server['db']);
        $this->connectServ->prepare($request)->execute();
    }

    public function dropDatabase(): void
    {
        $this->connectServ->prepare("DROP DATABASE IF EXISTS `{$this->server['db']['name']}`")->execute();
    }

    public function listTablesFromDb(): ?array
    {
        $stmt = App::getPdo()->query('SHOW TABLES');
        $result = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (empty($result))
        {
            return null;
        }
        return $result;
    }

    public function createTables(array $tables): void
    {
        foreach ($tables as $table)
        {
            $this->createTable($table);
        }
    }

    public function createTable(string $table): void
    {   
        $request = $this->mountTableRequest($table);
        App::getPdo()->prepare($request)->execute();
    }

    public function dropTable(string $table): void
    {
        App::getPdo()->prepare("DROP TABLE IF EXISTS `{$table}`")->execute();
    }

    public function searchForeignKeyFromTable(string $parentTable): ?string
    {
        $tables = $this->listTablesFromDb();
        foreach ($tables as $table)
        {
            $tableInfos = TableInfos::get($table);
            foreach ($tableInfos['schema'] as $column => $rules)
            {
                if (isset($rules['foreignKey']['table']) && $rules['foreignKey']['table'] === $parentTable)
                {
                    return $table;
                }
            }
        }
        return null;
    }

    public function searchForeignKeyOnAbsentTable(string $table): ?string
    {
        $tables = $this->listTablesFromDb();
        $tableInfos = TableInfos::get($table);
        foreach ($tableInfos['schema'] as $column => $rules)
        {
            if (isset($rules['foreignKey']['table']) && (is_null($tables) || array_search($rules['foreignKey']['table'], $tables) === false))
            {
                return $rules['foreignKey']['table'];
            }
        }
        return null;
    }


    private function mountDbRequest(array $db): string
    {
        return "CREATE DATABASE IF NOT EXISTS `{$db['name']}` DEFAULT CHARACTER SET {$db['default character']}";
    }

	private function mountTableRequest(string $table): string
    {
    	$table = TableInfos::get($table);
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

    private function mountTableColumns(array $request, string $column, array $rules): array
    {
        // markers that will help replace with values
        $request['column'] = "`$column` {{ type }}{{ maxLength }} {{ default }}";

        foreach ($rules as $rule => $value)
        {
            // email, password are only used with model and validator
            if ($rule === 'type')
            {
                if ($value === 'email' || $value === 'password')
                {
                    $value = 'varchar';
                }
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
                    if (isset($value['table']) && isset($value['column']))
                    {
                        $id = "{$value['table']}_{$value['column']}";

                        $constraint = isset($value['constraint']) && $value['constraint'] === true ? "CONSTRAINT `fk_{$id}`" : '';
                        $fk = "FOREIGN KEY (`{$id}`)";
                        $ref = "REFERENCES `{$value['table']}` (`{$value['column']}`)";

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