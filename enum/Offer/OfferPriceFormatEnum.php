<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferPriceFormatEnum extends AbstractEnum
{
	public const PER_M2_YEAR = 1;
	public const PER_M2_MONTH = 2;
	public const PER_M2_DAY = 3;
	public const ALL_PER_MONTH = 4;

	public static function labels(): array
	{
		return [
			self::PER_M2_YEAR => 'за м2/год',
			self::PER_M2_MONTH => 'за м2/месяц',
			self::PER_M2_DAY => 'за м2/день',
			self::ALL_PER_MONTH => 'за все в месяц',
		];
	}
}
