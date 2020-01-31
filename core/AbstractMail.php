<?php

namespace Core;

use Core\App;

abstract class AbstractMail
{
	private static $init;

	public static function send(string $to, array $vars = [])
	{
		if (!self::$init)
		{
			$file = App::getAppDirectory() . 'Config/security.json';
			$config = json_decode(file_get_contents($file), true)['mail'];
			self::initSmtp($config);
			self::$init = true;
		}

		$header = static::getHeader();
		$subject = static::getSubject();
		$message = static::getMessage($vars);

		mail($to, $subject, $message, $header);
	}

	private static function initSmtp(array $config): void
	{	
		ini_set('SMTP', $config['smtp']);
		ini_set('sendmail_from', $config['sendmail_from']);
		ini_set('smtp_port', $config['smtp_port']);
	}
}