<?php

namespace Core;

abstract class AbstractTemplate
{
	public static function load(string $view, string $method, array $varPage): void
	{
		$varPage['javascript'] = self::importAssets('javascript', $varPage);
		$varPage['css'] = self::importAssets('css', $varPage);
		$varPage['content'] = call_user_func_array([$view, $method], [$varPage]);
		static::display($varPage);
	}

	protected static function importAssets(string $fileType, array $varPage): ?string
	{
		$folder = $fileType === 'javascript' ? 'js' : 'css';

		if (!isset($varPage[$fileType]))
		{
			return null;
		}

		ob_start();
		foreach ($varPage[$fileType] as $fileName) 
		{
			$path = "/public/{$folder}/" . $fileName . ".{$folder}";
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
			
			?><script type="text/<?=$fileType?>" src="<?=$path?>"></script><?php
		}
		return ob_get_clean();
	}

	abstract public static function display(array $varPage): void;
}