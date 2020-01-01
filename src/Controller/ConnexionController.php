<?php

namespace App\Controller;

Class ConnexionController extends ViewManager
{
	public function show()
	{
		$this->loadPage(['ConnexionView', 'show'], ['title' => 'CONNEXION', 'h1' => 'CONNEXION']);
	}

	public function check()
	{
		var_dump($_POST);
		$this->loadPage(['ConnexionView', 'show'], ['title' => 'CONNEXION', 'h1' => 'CONNEXION']);
	}	
}