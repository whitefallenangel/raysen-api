<?php

namespace app\enum\ParkingAndEntrance;

use app\enum\AbstractEnum;

class ParkingPriceTypeEnum extends AbstractEnum
{
	public const PAID = 1;
	public const FREE = 2;

	public static function labels(): array
	{
		return [
			self::PAID => 'Платная',
			self::FREE => 'Бесплатная',
		];
	}
}