<?php

namespace App\Controller;

Class TestController extends ViewManager
{
	public function __construct()
	{
		$this->varPage = [
			'title' => 'APP-PHP::TEST',
			'h1' => 'APP-PHP',
			'h2' => 'PAGE DE TEST',
		];
	}

	public function show($id, $slug)
	{
		$this->varPage['id'] = $id;
		$this->varPage['slug'] = $slug;
		$this->renderer(['TestView', 'show']);
	}	
}