<?php

namespace Core\Devboard;

class ModelGenerator
{
	private $pathModel;

	public function __construct()
	{
		$this->pathModel = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Model' . DIRECTORY_SEPARATOR;
	}

	public function listModels(): ?array
    {
    	$result =  array_values(array_diff(scandir($this->pathModel), ['..', '.']));
        if (empty($result))
        {
            return null;
        }
		return array_map('strtolower', preg_replace('/\.php/', '', $result));
    }

	public function createModels(array $tables): void
	{
		foreach ($tables as $table)
		{
			$this->createModel($table);
		}
	}

	public function createModel(string $table): void
	{
		$table = ucfirst($table);
		$filename = $this->pathModel . "$table.php";

		if (!file_exists($filename))
		{
			$content = $this->mountContent($table);
		    $file = fopen($filename, "x+");
		    fputs($file, $content );
		    fclose($file);
		}
	}

	public function dropModel(string $table): void
	{
		$table = ucfirst($table);
		$filename = $this->pathModel . "$table.php";
		unlink($filename);
	}

	private function mountContent(string $table): string
	{
		// get schema of the table
		$class = 'App\\Schema\\' . $table . 'Schema';
		$schema = $class::$schema;

		// mount intro and constructor
		$intro = "<?php\n\nnamespace App\Model;\n\nuse Core\AbstractModel;\n\nclass $table extends AbstractModel\n{";
		$construct = "\n\n\tpublic function __construct()\n\t{\n\t\tparent::__construct(__CLASS__);\n\t}";

		$variables = '';
		$methods = '';
		foreach ($schema as $column => $rules)
		{
			// mount variables
			$variables .= "\n\tprivate \$$column;";

			// mount getter and setter
			$ucfColumn = ucfirst($column);

			$getterContent = "return \$this->$column;";
			$setterContent =  "\$this->$column = \$$column;\n\n\t\treturn \$this;";

			// manage special cases - types
			switch ($rules['type'])
			{
				case 'int':
					$type = 'int';
					break;
				case 'bool':
					$type = 'bool';
					break;
				case 'datetime':
					$type = '\DateTime';
					$getterContent = "if (is_null(\$this->$column))\n\t\t{\n\t\t\treturn null;\n\t\t}\n\t\treturn new \DateTime(\$this->$column);";
					break;
				case 'password':
					$type = 'string';
					$setterContent = "\$this->$column = password_hash(\$$column, PASSWORD_DEFAULT);\n\n\t\treturn \$this;";
					break;
				default:
					$type = 'string'; break;
			}

			$getter = "\n\n\tpublic function get$ucfColumn(): ?$type\n\t{\n\t\t$getterContent\n\t}";
			$setter = '';
			if (isset($rules['default']) && preg_match('/auto_increment|current_timestamp/', strtoupper($rules['default'])) === 0)
			{
				$setter = "\n\n\tpublic function set$ucfColumn($type \$$column): self\n\t{\n\t\t$setterContent\n\t}";
			}
			$methods .= $getter . $setter;
		}

		return ($intro . $variables . $construct . $methods . "\n}");
	}
}