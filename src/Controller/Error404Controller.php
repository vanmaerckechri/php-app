<?php

namespace App\Controller;

class Error404Controller extends AbstractController
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::404',
			'h1' => 'APP-PHP',
			'h2' => 'PAGE INTROUVABLE',
			'jsFileNames' => ['confirmPassword']
		];
	}
	public function show()
	{
		$this->renderer(['error404View', 'show']);
	}	
}