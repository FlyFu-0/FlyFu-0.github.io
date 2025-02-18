<?php

namespace Helpers;

class Tools
{
	public static function get_ip()
	{
		$value = '';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$value = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$value = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
			$value = $_SERVER['REMOTE_ADDR'];
		}

		return $value;
	}

	public static function bbCodeCustomizer(): \Helpers\BBCode\BBCode
	{
		$bbCode = new \Helpers\BBCode\BBCode();

		$bbCode->addTag('sup', function ($tag, &$html, $openingTag) {
			return ($tag->opening) ? '<sup>' : '</sup>';
		});

		$bbCode->addTag('sub', function ($tag, &$html, $openingTag) {
			return ($tag->opening) ? '<sub>' : '</sub>';
		});

		$bbCode->addTag('ul', function ($tag, &$html, $openingTag) {
			return ($tag->opening) ? '<ul>' : '</ul>';
		});

		$bbCode->addTag('li', function ($tag, &$html, $openingTag) {
			return ($tag->opening) ? '<li>' : '</li>';
		});

		$bbCode->addTag('ol', function ($tag, &$html, $openingTag) {
			return ($tag->opening) ? '<ol>' : '</ol>';
		});

		return $bbCode;
	}
}


