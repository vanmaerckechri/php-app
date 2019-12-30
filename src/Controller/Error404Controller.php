<?php

namespace App\Controller;

Class Error404Controller extends ViewManager
{
	public function show()
	{
		$this->loadPage(['error404View', 'show'], ['title' => '404 PAGE', 'h1' => '404 PAGE']);
	}	
}