<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferCurrencyEnum extends AbstractEnum
{
	public const RUBLES = 1;
	public const DOLLARS = 2;
	public const EURO = 3;
	public const YUAN = 4;

	public static function labels(): array
	{
		return [
			self::RUBLES => 'Рубли',
			self::DOLLARS => 'Доллары',
			self::EURO => 'Евро',
			self::YUAN => 'Юани',
		];
	}
}
