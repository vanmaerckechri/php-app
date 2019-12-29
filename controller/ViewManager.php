<?php

Class ViewManager
{
	private function loadPage($fileName, $content)
	{
		$page = array();
		$page['title'] = $content['title'];
		$page['h1'] = $content['h1'];

		require_once("../view/$fileName.php");
		require_once('../view/common/template.php');
	}

	public function loadHomePage()
	{
		$this->loadPage('viewHome', ['title' => 'HOME PAGE', 'h1' => 'HOME PAGE']);
	}

	public function load404Page()
	{
		$this->loadPage('view404', ['title' => '404 PAGE', 'h1' => '404 PAGE']);
	}

	public function loadTestPage($id, $slug)
	{
		$this->loadPage('viewHome', ['title' => 'TEST PAGE', 'h1' => "TEST PAGE id = $id et slug = $slug"]);
	}
}