<?php

namespace Core\Devboard;

class RepoGenerator extends abstractClassGenerator
{
	protected $ext = 'Repository';
	protected $directory = 'Repository';

	protected function mountContent(string $table): string
	{
		return "<?php\n\nnamespace App\Repository;\n\nuse Core\AbstractRepository;\nuse Core\Request;\n\nclass {$table}Repository extends AbstractRepository\n{\n}";
	}
}