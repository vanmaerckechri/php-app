<?php

namespace App\Controller;

use Core\ {
	Migration\Migration,
	Migration\DbContentGenerator,
	AbstractController
};

class DevboardController extends AbstractController
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::DEVBOARD',
			'h1' => 'APP-PHP',
			'h2' => 'Devboard',
		];
	}

	public function index()
	{
		$this->renderer(['DevboardView', 'index']);

		$migration = new Migration();
		$migration->createDb();
		$migration->createTables(['user', 'article', 'category']);

		DbContentGenerator::launch([
			'user' => ['iteration' => 5, 'forceRand' => ['created_at']],
			'category' => ['iteration' => 3],
			'article' => ['iteration' => 30, 'forceRand' => ['created_at']]
		]);
	}	
}