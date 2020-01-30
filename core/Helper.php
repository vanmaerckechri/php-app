<?php

namespace Core;

use PDO;
use Core\App;

class Helper
{
	public static function slugify(string $string): string
	{
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
	}
}