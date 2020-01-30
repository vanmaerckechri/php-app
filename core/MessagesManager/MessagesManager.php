<?php

namespace Core\MessagesManager;

use Core\App;

class MessagesManager
{
	private static $messages = [];
	private static $currentMessages;

	public static function loadMessages()
	{
		if (!self::$messages)
		{
			$file = $_SERVER['DOCUMENT_ROOT'] . '/core/MessagesManager/messages.json';
			self::$messages = json_decode(file_get_contents($file), true);

        	$customFile = App::getAppDirectory() . 'Config/messages.json';
			if (file_exists($customFile))
			{
				$customSms = json_decode(file_get_contents($file), true);
				self::$messages = array_unique(array_merge(self::$messages, $customSms), SORT_REGULAR);
			}
		}
	}

	public static function add(array $datas, bool $isCustomSms = false, string $customType = 'info'): void
	{
		self::loadMessages();

		foreach ($datas as $keyFam => $messages)
		{
			foreach ($messages as $key => $value)
			{
				if ($isCustomSms === false)
				{
					$language = App::getConfig('language');
					$sms = self::$messages[$key]['content'][$language];
					$sms = str_replace('{{x}}', $value, $sms);
					$smsType = self::$messages[$key]['type'];
				}
				else
				{
					$sms = $value;
					$smsType = $customType;
				}
				$deb[] = $sms;
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
				foreach ($messages as $key => $value)
				{
				?>
					<p class="sms-<?=$value['type']?>"><?=$value['sms']?></p>
				<?php
				}
				$result[$keyFam] = ob_get_clean();
			}
		}

		$_SESSION['messages'] = [];

		return $result;
	}
}
