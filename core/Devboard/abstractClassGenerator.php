<?php

namespace Core\Devboard;

abstract class abstractClassGenerator
{
	private $pathModel;

	public function __construct()
	{
		$this->pathModel = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $this->directory . DIRECTORY_SEPARATOR;
		$this->createDirectory();
	}

	public function list(): ?array
    {
    	$result =  array_values(array_diff(scandir($this->pathModel), ['..', '.']));
        if (empty($result))
        {
            return null;
        }
		return array_map('strtolower', preg_replace('/\.php/', '', $result));
    }

	public function createMultiples(array $tables): void
	{
		foreach ($tables as $table)
		{
			$this->create($table);
		}
	}

	public function create(string $table): void
	{
		$table = ucfirst($table);
		$filename = $this->pathModel . $table . $this->ext . '.php';

		if (!file_exists($filename))
		{
			$content = $this->mountContent($table);
		    $file = fopen($filename, "x+");
		    fputs($file, $content );
		    fclose($file);
		}
	}

	public function drop(string $table): void
	{
		$table = ucfirst($table);
		$filename = $this->pathModel . $table . $this->ext . '.php';
		unlink($filename);
	}

	private function createDirectory(): void
	{
		if (!file_exists($this->pathModel))
		{
    		mkdir($this->pathModel, 0777, true);
		}
	}
}