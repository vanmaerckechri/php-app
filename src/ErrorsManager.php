<?php

namespace App;

class ErrorsManager
{
	private static $errors = [];
	private static $errorMessages;

	public static function loadMessages()
	{
		if (!self::$errorMessages)
		{
			self::$errorMessages = require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/errors.php';
		}
	}

	public static function add(array $datas, bool $isCustomSms = false): void
	{
		self::loadMessages();

		foreach ($datas as $keyFam => $errors)
		{
			foreach ($errors as $key => $value)
			{
				if ($isCustomSms === false)
				{
					$sms = self::$errorMessages[$key][$GLOBALS['lang']];
					$sms = str_replace('{{x}}', $value, $sms);
				}
				else
				{
					$sms = $value;
				}
				self::$errors[$keyFam][] = $sms;
			}
		}
	}

	public static function getMessages(): array
	{
		$result = array();

		foreach (self::$errors as $keyFam => $errors) 
		{
			ob_start();
			?>
				<div class="error-container">
			<?php
			foreach ($errors as $key => $value)
			{
			?>
				<p class="error"><?=$value?></p>
			<?php
			}
			?>
				</div>
			<?php
			$result[$keyFam] = ob_get_clean();
		}

		return $result;
	}
}
