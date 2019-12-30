<?php

namespace App\Controller;

Class HomeController extends ViewManager
{
	public function show()
	{
		$this->loadPage('home_show', ['title' => 'HOME PAGE', 'h1' => 'HOME PAGE']);
	}	
}