<?php

namespace Core;

abstract class AbstractController
{
	protected $varPage = array();

	protected function renderer(string $class, string $method): void
	{
		$class = "App\View\\$class";
		$this->varPage['content'] = call_user_func_array([$class, $method], [$this->varPage]);
		call_user_func_array(['App\View\Template', 'load'], [$this->varPage]);
	}
}