<?php

namespace Core\Devboard;

class ModelGenerator extends abstractClassGenerator
{
	protected $ext = '';
	protected $directory = 'Model';

	protected function mountContent(string $table): string
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