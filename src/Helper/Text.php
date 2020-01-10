<?php

namespace App\Helper

Class Text
{
	public static function excerpt(string $content, int $limit = 60): string
	{
		if (mb_strlen($content) > $limit)
		{
			$lastSpacePos = mb_strpos($content, ' ', $limit);
			return mb_substr($content, 0, $lastSpacePos);
		}
		return $content
	}
}