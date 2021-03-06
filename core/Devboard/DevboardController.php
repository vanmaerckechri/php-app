<?php

namespace Core\Devboard;

use PDO;
use Core\ {
	App,
	Router\Router,
	MessagesManager\MessagesManager
};

class DevboardController
{
	public function index()
	{
		$migration = new Migration();
		$varPage['dbName'] = $migration->getDbName();
		$varPage['isDbExist'] = $migration->checkDbExist();
		if ($varPage['isDbExist'])
		{
			$varPage['schemas'] = $this->getTablesFromSchemas();
			$varPage['tablesFromDb'] = $migration->listTablesFromDb();

			$entityGenerator = new EntityGenerator();
			$varPage['modelList'] = $entityGenerator->list();

			$repoGenerator = new RepoGenerator();
			$varPage['repoList'] = $repoGenerator->list();
		}

		$varPage['messages'] = MessagesManager::getMessages();

		DevboardView::index($varPage);
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

		header('Location: ' . Router::url('devboard'));
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

		header('Location: ' . Router::url('devboard'));
		exit();
	}

	private function getTablesFromSchemas()
	{
		$files =  array_values(array_diff(scandir(App::getAppDirectory() . 'Schema/'), ['..', '.']));
		return array_map('strtolower', preg_replace('/Schema.php/', '', $files));
	}
}