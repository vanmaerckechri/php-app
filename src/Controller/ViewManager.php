<?php

namespace App\Controller;

Class ViewManager
{
	protected function loadPage($view, $varPage)
	{
		$class = "App\View\\$view[0]";
		$method = $view[1];
		$varPage['content'] = call_user_func_array([$class, $method], [$varPage]);
		call_user_func_array(['App\View\Template', 'load'], [$varPage]);
	}
}