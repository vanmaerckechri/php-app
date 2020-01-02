<?php

namespace App;

class ErrorsManager
{
	private static $errorMessages;

	public static function getMessage(array $errorName, string $lang): array
	{
		if (!self::$errorMessages)
		{
			self::$errorMessages = require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/errors.php';
		}

		$result = array();
		foreach ($errorName as $v) 
		{
			$sms = self::$errorMessages[$v][$lang];
			ob_start();
			?>
				<p class="error"><?=$sms?></p>
			<?php
			$result[$v] = ob_get_clean();
		}
		return $result;
	}
}
