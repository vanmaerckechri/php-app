<?php

namespace Core\Devboard;

use Core\App;

trait TableInfos
{
	public static function get(string $table): array
	{
		$class = App::getClass('schema', $table);

		return array(
            'name' => $table,
			'schema' => $schema = $class::$schema,
    		'options' => $options = $class::$options
    	);
	}
}