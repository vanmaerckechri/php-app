<?php

namespace Core;

use Core\ {
	App,
	MessagesManager\MessagesManager
};

class FilesManager
{
	private static $error = array();
	private static $path;
	private static $keyFile;
	private static $schema;
	private static $maxSize;
	private static $ext;
	private static $fileName;

	public static function uploadImage(string $path, string $keyFile, array $schema, bool $required, int $maxSize = 1500000): bool
	{
		self::initVar($path, $keyFile, $schema, $maxSize);

		if (self::submitted($required) && self::checkIsImageFile())
		{
			self::uploadFile();
		}
		return self::noErrorDetected();
	}

	public static function getLastFileName(): ?string
	{
		return self::$fileName;
	}

	public static function dropFile(string $path, ?string $fileName, bool $isProtectFiles = true): bool
	{
		if ($fileName)
		{
			$appDir = App::getConfig('autoload')['directory'];
			if ($isProtectFiles === false || preg_match("#^/(core/|$appDir)#", $path) == 0)
			{
				$file = $_SERVER['DOCUMENT_ROOT'] . $path . $fileName;
				if (file_exists($file))
				{
					unlink($file);
					return true;
				}
			}
		}
		return false;
	}

	private static function initVar(string $path, string $keyFile, array $schema, int $maxSize): void
	{
		self::$error['uploadSms'] = [];
		self::$path = $_SERVER['DOCUMENT_ROOT'] . $path;
		self::$keyFile = $keyFile;
		self::$schema = $schema;
		self::$maxSize = $maxSize;
	}

	private static function uploadFile(): void
	{
		if (self::checkSize())
		{
			self::moveUploadedFile();
		}
	}

	private static function submitted($required): bool
	{
		if ($_FILES[self::$keyFile]['error'] === 4)
		{
			if ($required)
			{
				self::$error['uploadSms']['uploadFileRequired'] = null;
			}
			return false;
		}
		return true;
	}

	private static function checkIsImageFile(): bool
	{
		$info = new \finfo(FILEINFO_MIME_TYPE);

		if (false !== $ext = array_search(
			$info->file($_FILES[self::$keyFile]['tmp_name']),
			array(
	            'jpg' => 'image/jpeg',
	            'png' => 'image/png',
	            'gif' => 'image/gif',
	        ),
	        true
    	)) 
    	{
			self::$ext = $ext;
    		return true;
    	}
    	self::$error['uploadSms']['uploadFileType'] = null;
    	return false;
	}

	private static function moveUploadedFile(): bool
	{
		self::randFileName();
		$uploadfile = self::$path . self::$fileName;
		if (!file_exists(self::$path))
		{
    		mkdir(self::$path, 0777, true);
		}
		if (move_uploaded_file($_FILES[self::$keyFile]['tmp_name'], $uploadfile))
		{
			return true;
		}
		self::$error['uploadSms']['uploadFileMove'] = null;
		return false;
	}

	private static function randFileName()
	{
		$min = self::$schema['minLength'] ? self::$schema['minLength'] : 10;
		$max = self::$schema['maxLength'] ? self::$schema['maxLength'] : 18;
		$chars = 'bcdfghjklmnpqrstvwxzaeiouy0123456789';
		$charsLength = strlen($chars) - 1;
		$fileName = '';
		do
		{
			$length = rand($min, $max);
			for ($i = $length; $i >= 0; $i--)
			{
				$rand = rand(0, $charsLength);
				$fileName .= $chars[$rand];
			}
		}
		while (file_exists(self::$path . $fileName));

		self::$fileName = $fileName . '.' . self::$ext;
	}

	private static function checkSize(): bool
	{
		if ($_FILES[self::$keyFile]['size'] < self::$maxSize)
		{
			return true;
		}
		self::$error['uploadSms']['uploadFileSize'] = null;
		return false;
	}

	private static function noErrorDetected(): bool
	{
		if (!empty(self::$error['uploadSms']))
		{
			MessagesManager::add(self::$error);
			return false;
		}
		return true;		
	}
}