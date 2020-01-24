<?php

namespace App\Controller;

use Core\AbstractController;

class Error404Controller extends AbstractController
{
	protected $varPage = [
		'title' => 'APP-PHP::404',
		'h1' => 'APP-PHP',
		'h2' => 'PAGE INTROUVABLE',
	];

	public function show()
	{
		$this->renderer('error404View', 'show');
	}	
}