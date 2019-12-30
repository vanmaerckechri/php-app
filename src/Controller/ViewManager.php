<?php

namespace App\Controller;

Class ViewManager
{
	protected function loadPage($fileName, $varPage)
	{
		require_once("../view/$fileName.php");
		require_once('../view/template.php');
	}
}