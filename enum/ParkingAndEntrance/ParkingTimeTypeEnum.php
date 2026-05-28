<?php

namespace app\enum\ParkingAndEntrance;

use app\enum\AbstractEnum;

class ParkingTimeTypeEnum extends AbstractEnum
{
	public const HOUR = 1;
	public const DAY = 2;
	public const MONTH = 3;

	public static function labels(): array
	{
		return [
			self::HOUR => 'Час',
			self::DAY => 'Сутки',
			self::MONTH => 'Месяц',
		];
	}
}