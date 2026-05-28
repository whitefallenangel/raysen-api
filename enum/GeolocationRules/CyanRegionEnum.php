<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class CyanRegionEnum extends AbstractEnum
{
	public const MOSCOW = 1;
	public const NEAR_MOSCOW = 2;
	public const FAR_MOSCOW = 3;
	public const REGIONS = 4;

	public static function labels(): array
	{
		return [
			self::MOSCOW => 'Москва',
			self::NEAR_MOSCOW => 'Ближнее Подмосковье',
			self::FAR_MOSCOW => 'Дальнее Подмосковье',
			self::REGIONS => 'Регионы',
		];
	}
}