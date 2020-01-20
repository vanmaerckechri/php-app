<?php

namespace Core\Devboard;

Class ContentGenerator
{
	public static function generatePhrase(int $minLength, int $maxLength, bool $isPunctuation = true, bool $isLineBreak = true, bool $isFinalPunct = true): string
	{
		$punctuation = array('. ', '! ', '? ');
		$phraseMaxLength = rand($minLength, $maxLength);
		$phrase = '';

		do
		{
			$min = 2;
			$max = 12;
			$isOver = false;
			$remainingSpace = $phraseMaxLength - mb_strlen($phrase);

			// if is the final itération, adapt the final word
			if ($remainingSpace < (2 * $max))
			{
				$min = --$max;
				$isOver = true;
			}

			$word = self::generateWord($min, $max);

			// if is not the final itération, manage puncutation, space, line break
			if ($isOver === false)
			{
				// punctuation or space ?
				if (!$isPunctuation || rand(0, 5) > 0)
				{
					$punct = ' ';		
				}
				else
				{
					$punct = self::randChar($punctuation);
				}		

				// detect if the sentence ends with punctuation 
				if (preg_match('/(\. )|(\! )|(\? )/', mb_substr($phrase, -2), $matches))
				{
					// uppercase first letter after punctuation
					$word = ucfirst($word);

					// rand line break
					if ($isLineBreak === true && rand(0, 5) === 0)
					{
						$phrase .= "\n";
					}
				}
			}
			else
			{
				$punct = $isFinalPunct === true ? '.' : '';
			}

			$phrase .= $word . $punct;
		}
		while($isOver === false);

		$phrase = ucfirst($phrase);

		return $phrase;
	}

	public static function generateWord(int $minLength, int $maxLength): string
	{
		$wordMaxLength = rand($minLength, $maxLength);
		$consonants = 'bcdfghjklmnpqrstvwxz';
		$vowels = 'aeiouy';

		$word = '';
		$lettersSrc = rand(0, 1) === 1 ? $vowels : $consonants;

		do
		{
			// random letter
			$letter = self::randChar($lettersSrc);

			if ($wordMaxLength > (mb_strlen($word) + 1))
			{
				// double consonants or vowels ?
				if (rand(0, 1) === 0)
				{
					$letter .= self::randChar($lettersSrc);
				}
				else
				{
					// add another consonant ?
					if ($lettersSrc === $consonants && rand(0, 5) === 0)
					{
						$letter .= $letter;
					}
				}
				// switch letters src
				$lettersSrc = $lettersSrc === $vowels ? $consonants : $vowels;
			}
			// update word
			$word .= $letter;

		} while (mb_strlen($word) < $wordMaxLength);

		return $word;
	}

	public static function generateEmail(int $min, int $max = 24): string
	{
		$email = '';
		$length = rand($min, $max);
		// min email size
		$length = $length < 5 ? 5 : $length;
		// -2 for '@'' and '.'
		$length -= 2;

		$charTemp = self::generateWord($length, $length);	

		$atPos = floor(mb_strlen($charTemp) / (rand(15, 20) / 10));
		
		$remainingLength = mb_strlen($charTemp) - $atPos;
		$beforeExtLength = $remainingLength - (rand(2, 3));
		$extPos = $beforeExtLength < 1 ? 1 : $beforeExtLength;
		$extPos += $atPos + 1;

		$email = substr_replace($charTemp, '@', $atPos, 0);
		$email = substr_replace($email, '.', $extPos, 0);

		return $email;
	}

	public static function generateInteger(int $min, int $max): int
	{
		$min = pow(10, $min);
		$max = pow(10, $max);
		return rand($min, $max);
	}

	public static function generateDatetime(): string
	{
		$timestamp = mt_rand(1, time());
		return date("Y-m-d H:i:s", $timestamp);
	}

	private static function randChar($chars): string
	{
		$i = is_array($chars) ? rand(0, count($chars) - 1) : rand(0, strlen($chars) - 1);
		return $chars[$i];
	}
}