<?php

namespace app\enum\GeolocationRules;

use app\enum\AbstractEnum;

class HighwayTypeEnum extends AbstractEnum
{
	public const GENERAL = 1;
	public const MOSCOW  = 2;

	public static function labels(): array
	{
		return [
			self::GENERAL => 'Общее',
			self::MOSCOW  => 'Московское',
		];
	}
}