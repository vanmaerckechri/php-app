<?php

namespace App\Controller;

Class HomeController extends ViewManager
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::HOME',
			'h1' => 'APP-PHP',
			'h2' => 'HOME',
		];
	}

	public function show()
	{
		$this->loadPage(['HomeView', 'show']);
	}	
}