<?php

namespace Core\Devboard;

use PDO;
use Core\ {
	App,
	AbstractController,
	MessagesManager\MessagesManager
};

class DevboardController extends AbstractController
{
	public function index()
	{
		$migration = new Migration();
		$dbName = $migration->getDbName();
		$isDbExist = $migration->checkDbExist();
		if ($isDbExist)
		{
			$schemas = $this->getTablesFromSchemas();
			$tablesFromDb = $migration->listTablesFromDb();

			$entityGenerator = new EntityGenerator();
			$modelList = $entityGenerator->list();

			$repoGenerator = new RepoGenerator();
			$repoList = $repoGenerator->list();
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
			$absentTable = $migration->searchForeignKeyOnAbsentTable($_POST['table']);
			if ($absentTable)
			{
				MessagesManager::add(["{$_POST['table']}TableSms" => ['sql_foreignKeyOnAbsentTable' => $absentTable]]);
			}
			else
			{
				$migration->createTable($_POST['table']);
			}
		}
		else if ($_POST['context'] === 'hydrate')
		{
			$emptyTable = FillDatabase::searchForeignKeyOnEmptyTable($_POST['table']);
			if ($emptyTable)
			{
				MessagesManager::add(["{$_POST['table']}FillSms" => ['sql_foreignKeyOnEmptyTable' => $emptyTable]]);
			}
			else
			{
				FillDatabase::createRows([$_POST['table'] => ['iteration' => $_POST['iteration'], 'forceRand' => ['created_at']]]);
			}
		}
		else if ($_POST['context'] === 'model')
		{
			$entityGenerator = new EntityGenerator();
			$entityGenerator->create($_POST['model']);
		}
		else if ($_POST['context'] === 'repo')
		{
			$repoGenerator = new RepoGenerator();
			$repoGenerator->create($_POST['repo']);
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
			$fromTable = $migration->searchForeignKeyFromTable($_POST['table']);
			if ($fromTable)
			{
				MessagesManager::add(["{$_POST['table']}TableSms" => ['sql_foreignKeyFromAnotherTable' => $fromTable]]);
			}
			else
			{
				$migration->dropTable($_POST['table']);
			}
		}
		else if ($_POST['context'] === 'model')
		{
			$entityGenerator = new EntityGenerator();
			$entityGenerator->drop($_POST['model']);
		}
		else if ($_POST['context'] === 'repo')
		{
			$repoGenerator = new RepoGenerator();
			$repoGenerator->drop($_POST['repo']);
		}

		$this->redirect('devboard');
		exit();
	}

	private function getTablesFromSchemas()
	{
		$files =  array_values(array_diff(scandir(App::getAppDirectory() . 'Schema/'), ['..', '.']));
		return array_map('strtolower', preg_replace('/Schema.php/', '', $files));
	}
}