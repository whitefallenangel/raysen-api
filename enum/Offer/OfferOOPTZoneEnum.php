<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferOOPTZoneEnum extends AbstractEnum
{
	public const YES = 1;
	public const NO = 2;
	public const PARTIALLY = 3;

	public static function labels(): array
	{
		return [
			self::YES => 'Есть',
			self::NO => 'Нет',
			self::PARTIALLY => 'Частично',
		];
	}
}