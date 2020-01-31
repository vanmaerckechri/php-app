<?php

namespace Core\Devboard;

use Core\App;

class EntityGenerator extends abstractFileGenerator
{
	protected $ext = '';
	protected $directory = 'Entity';

	protected function mountContent(string $table): string
	{
		// get schema of the table
		$schemaClass = App::getClass('schema', $table);
		$schema = $schemaClass::$schema;

		// mount intro and constructor
		$namespace = App::getConfig('autoload')['namespace'];
		$intro = "<?php\n\nnamespace {$namespace}Entity;\n\nuse Core\AbstractEntity;\n\nclass $table extends AbstractEntity\n{";
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
			if (isset($rules['default']) && preg_match('/auto_increment|current_timestamp/', $rules['default']) === 0)
			{
				$type = strpos($rules['default'], 'not null') === false ? '?' . $type : $type;

				$setter = "\n\n\tpublic function set$ucfColumn($type \$$column): self\n\t{\n\t\t$setterContent\n\t}";
			}
			$methods .= $getter . $setter;
		}

		return ($intro . $variables . $construct . $methods . "\n}");
	}
}