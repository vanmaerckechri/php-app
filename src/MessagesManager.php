<?php

namespace App;

class MessagesManager
{
	private static $messages = [];
	private static $currentMessages;

	public static function loadMessages()
	{
		if (!self::$messages)
		{
			self::$messages = require_once $_SERVER['DOCUMENT_ROOT'] . '/src/Config/messages.php';
		}
	}

	public static function add(array $datas, $isCustomSms = false, string $customType = 'info'): void
	{
		self::loadMessages();

		foreach ($datas as $keyFam => $messages)
		{
			foreach ($messages as $key => $value)
			{
				if ($isCustomSms === false)
				{
					$sms = self::$messages[$key]['content'][$GLOBALS['lang']];
					$sms = str_replace('{{x}}', $value, $sms);
					$smsType = self::$messages[$key]['type'];
				}
				else
				{
					$sms = $value;
					$smsType = $customType;
				}
				$_SESSION['messages'][$keyFam][] = ['sms' => $sms, 'type' => $smsType];
			}
		}
	}

	public static function getMessages(): array
	{
		$result = array();

		if (isset($_SESSION['messages']))
		{
			foreach ($_SESSION['messages'] as $keyFam => $messages) 
			{
				ob_start();
				?>
					<div class="sms-container">
				<?php
				foreach ($messages as $key => $value)
				{
				?>
					<p class="sms-<?=$value['type']?>"><?=$value['sms']?></p>
				<?php
				}
				?>
					</div>
				<?php
				$result[$keyFam] = ob_get_clean();
			}
		}

		$_SESSION['messages'] = [];

		return $result;
	}
}
