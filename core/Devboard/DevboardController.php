<?php

namespace Core\Devboard;

use PDO;
use Core\Helper;
use Core\MessagesManager\MessagesManager;
use Core\AbstractController;

class DevboardController extends AbstractController
{
	public function index()
	{
		$migration = new Migration();
		$dbName = $migration->getDbName();
		$isDbExist = $migration->checkDbExist();
		if ($isDbExist)
		{
			Helper::getPdo()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$schemas = Helper::getTablesFromSchemas();
			$tablesFromDb = $migration->listTablesFromDb();

			$modelGenerator = new ModelGenerator();
			$modelList = $modelGenerator->listModels();
		}

		$varPage['messages'] = MessagesManager::getMessages();
		require 'DevboardView.php';
	}

	public function create()
	{
		if ($_POST['context'] === 'database')
		{
			$migration = new Migration();
			$migration->createDatabase();
		}
		else if ($_POST['context'] === 'table')
		{
			$migration = new Migration();
			$foreignKeyToTable = $migration->checkForeignKeyInside($_POST['table']);
			if ($foreignKeyToTable)
			{
				MessagesManager::add(["{$_POST['table']}Sms" => ['sql_insideForeignKey' => $foreignKeyToTable]]);
			}
			else
			{
				$migration->createTable($_POST['table']);
			}
		}
		else if ($_POST['context'] === 'model')
		{
			$modelGenerator = new ModelGenerator();
			$modelGenerator->createModel($_POST['model']);
		}
		else if ($_POST['context'] === 'hydrate')
		{
			FillDatabase::createRows([$_POST['table'] => ['iteration' => $_POST['iteration'], 'forceRand' => ['created_at']]]);
		}

		$this->redirect('devboard');
		exit();
	}

	public function delete()
	{		
		if ($_POST['context'] === 'database')
		{
			$migration = new Migration();
			$migration->dropDatabase();
		}
		else if ($_POST['context'] === 'table')
		{
			$migration = new Migration();
			$foreignKeyFromTable = $migration->checkForeignKeyOutside($_POST['table']);
			if ($foreignKeyFromTable)
			{
				MessagesManager::add(["{$_POST['table']}Sms" => ['sql_outsideForeignKey' => $foreignKeyFromTable]]);
			}
			else
			{
				$migration->dropTable($_POST['table']);
			}
		}
		else if ($_POST['context'] === 'model')
		{
			$modelGenerator = new ModelGenerator();
			$modelGenerator->dropModel($_POST['model']);
		}

		$this->redirect('devboard');
		exit();
	}
}