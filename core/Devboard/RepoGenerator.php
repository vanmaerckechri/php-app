<?php

namespace Core\Devboard;

use Core\App;

class RepoGenerator extends abstractFileGenerator
{
	protected $ext = 'Repository';
	protected $directory = 'Repository';

	protected function mountContent(string $table): string
	{
		$namespace = App::getConfig('autoload')['namespace'];
		return "<?php\n\nnamespace {$namespace}Repository;\n\nuse Core\ {\n\tAbstractRepository,\n\tRequest\n};\n\nclass {$table}Repository extends AbstractRepository\n{\n}";
	}
}