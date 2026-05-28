<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferPriceTypeEnum extends AbstractEnum
{
	public const PER_M2 = 1;//4
	public const PER_M2_YEAR = 2;//1
	public const PER_M2_MONTH = 3;//2
	public const PER_M2_DAY = 4;
	public const FOR_1_P_M = 5;//7
	public const FOR_1_ACRE = 6;
	public const FOR_1_HECTARE = 7;

	public static function labels(): array
	{
		return [
			self::PER_M2 => 'за м2',
			self::PER_M2_YEAR => 'за м2/год',
			self::PER_M2_MONTH => 'за м2/месяц',
			self::PER_M2_DAY => 'за м2/день',
			self::FOR_1_P_M => 'за 1 п.м.',
			self::FOR_1_ACRE => 'за 1 сотку',
			self::FOR_1_HECTARE => 'за 1 Га',
		];
	}
}