<?php

namespace App\Controller;

Class InscriptionController extends ViewManager
{
	public function show()
	{
		$this->loadPage(['InscriptionView', 'show'], ['title' => 'INSCRIPTION', 'h1' => 'INSCRIPTION', 'jsFileNames' => ['confirmPassword']]);
	}

	public function record()
	{
		var_dump($_POST);
		$this->loadPage(['InscriptionView', 'show'], ['title' => 'INSCRIPTION', 'h1' => 'INSCRIPTION', 'jsFileNames' => ['confirmPassword']]);
	}	
}