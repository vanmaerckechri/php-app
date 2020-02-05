<?php

namespace Core\Devboard;

use Core\App;

abstract class AbstractFileGenerator
{
	private $path;

	public function __construct()
	{
		$this->path = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . App::getConfig('autoload')['directory'] . DIRECTORY_SEPARATOR . $this->directory . DIRECTORY_SEPARATOR;
		$this->createDirectory();
	}

	public function list(): ?array
    {
    	$result =  array_values(array_diff(scandir($this->path), ['..', '.']));
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
		$filename = $this->path . $table . $this->ext . '.php';

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
		$filename = $this->path . $table . $this->ext . '.php';
		unlink($filename);
	}

	private function createDirectory(): void
	{
		if (!file_exists($this->path))
		{
    		mkdir($this->path, 0777, true);
		}
	}

	abstract protected function mountContent(string $table): string;
}