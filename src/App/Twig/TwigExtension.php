<?php declare(strict_types = 1);

namespace App\Twig;

use DateTimeImmutable;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

class TwigExtension extends Twig_Extension
{
	private $settings;

	public function __construct($settings)
	{
		$this->settings = $settings;
	}

	public function getName()
	{
		return 'App';
	}

	public function getFunctions()
	{
		return [
			new Twig_SimpleFunction('format_date', function ($date, $format = 'm/d/Y') {
				return (new DateTimeImmutable($date))->format($format);
			}),
		];
	}

	public function getFilters()
	{
		return [
			new Twig_SimpleFilter('truncate', function ($text, $maxCharacters) {
				$maxCharacters = (int) $maxCharacters;

				if (mb_strlen($text) < $maxCharacters)
				{
					return $text;
				}

				$text = mb_substr($text, 0, $maxCharacters);

				return (preg_match('/(^.+)[\s]+[^\s]*/u', $text, $matches) ? $matches[1] : $text) . '...';
			}),
		];
	}
}