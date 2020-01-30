<?php

namespace Core\Devboard;

use Core\Helper;

trait TableInfos
{
	public static function get(string $table): array
	{
		$class = Helper::getClass('schema', $table);

		return array(
            'name' => $table,
			'schema' => $schema = $class::$schema,
    		'options' => $options = $class::$options
    	);
	}
}