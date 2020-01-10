<?php

namespace App\Controller;

use App\Model\Article;
use App\Request\ArticleRequest;

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
		$this->renderer(['HomeView', 'show']);
	}	
}