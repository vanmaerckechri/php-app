<?php

namespace App\Controller;

Class TestController extends ViewManager
{
	public function show($id, $slug)
	{
		$this->loadPage(['TestView', 'show'], ['title' => 'TEST PAGE', 'h1' => 'TEST PAGE', 'id' => $id, 'slug' => $slug]);
	}	
}