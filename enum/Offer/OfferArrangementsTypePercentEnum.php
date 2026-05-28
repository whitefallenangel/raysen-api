<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferArrangementsTypePercentEnum extends AbstractEnum
{
	public const CASH = 1;
	public const CASHLESS = 2;
	public const THROUGH_THE_HOLIDAYS = 3;

	public static function labels(): array
	{
		return [
			self::CASH => 'Наличные',
			self::CASHLESS => 'Безналичный',
			self::THROUGH_THE_HOLIDAYS => 'Через каникулы',
		];
	}
}
