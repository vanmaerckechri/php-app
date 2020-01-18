<?php

namespace Core\Devboard;

use PDO;
use Core\Helper;
use Core\Devboard\ModelGenerator;

class DevboardController
{
	public function index()
	{
		$migration = new Migration();
		$dbName = $migration->getDbName();
		$isDbExist = $migration->checkDbExist();
		if ($isDbExist)
		{
			$schemas = Helper::getTablesFromSchemas();
			$tablesFromDb = $migration->listTablesFromDb();

			$modelGenerator = new ModelGenerator();
			$modelList = $modelGenerator->listModels();
		}

		require 'DevboardView.php';
	}

	public function create()
	{
		$this->execute('create');
	}

	public function delete()
	{
		$this->execute('drop');
	}

	public function execute(string $method): void
	{
		$method = $method . ucfirst($_POST['context']);

		if ($_POST['context'] === 'database')
		{
			$migration = new Migration();
			$migration->$method();
		}
		else if ($_POST['context'] === 'table')
		{
			$migration = new Migration();
			$migration->$method($_POST['table']);
		}
		else if ($_POST['context'] === 'model')
		{
			$modelGenerator = new ModelGenerator();
			$modelGenerator->$method($_POST['model']);
		}
		else if ($_POST['context'] === 'hydrate')
		{
			$dbContentGenerator = new DbContentGenerator();
			$dbContentGenerator->createRows([$_POST['table'] => ['iteration' => $_POST['iteration']]]);
		}

		header('Location: ' . $GLOBALS['router']->url('devboard'));
		exit();
	}
}