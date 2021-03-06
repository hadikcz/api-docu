<?php

declare(strict_types=1);

/**
 * @copyright   Copyright (c) 2016 ublaboo <ublaboo@paveljanda.com>
 * @author      Pavel Janda <me@paveljanda.com>
 * @package     Ublaboo
 */

namespace Ublaboo\ApiDocu;

class TemplateFilters
{
	public static function common(string $filter): ?string
	{
		if (method_exists(__CLASS__, $filter)) {
			$args = func_get_args();
			array_shift($args);

			$callback = [__CLASS__, $filter];

			if (!is_callable($callback)) {
				throw new \UnexpectedValueException;
			}

			return ($callback)($args);
		}
	}


	public static function description(array $text): string
	{
		$text = reset($text);
		$text = nl2br($text);
		$text = str_replace(["\n", "\n\r", "\r\n", "\r"], '', $text);

		$text = preg_replace_callback('/<json><br \/>(.*?)<\/json>/s', function ($item) {
			$s = '<br><pre class="apiDocu-json">' . str_replace('<br>', '', end($item)) . '</pre>';
			$s = (string) preg_replace('/(\s)"([^"]+)"/', '$1<span class="apiDocu-string">"$2"</span>', $s);
			$s = (string) preg_replace('/\/\/(.*?)<br \/>/', '<span class="apiDocu-comment">//$1</span><br>', $s);

			return $s;
		}, $text);

		$text = preg_replace('/\*\*([^*]*)\*\*/', '<strong>$1</strong>', (string) $text);

		return (string) $text;
	}
}
