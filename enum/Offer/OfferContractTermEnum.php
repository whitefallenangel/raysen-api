<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferContractTermEnum extends AbstractEnum
{
	public const LONG_TERM = 1;
	public const SHORT_TERM = 2;

	public static function labels(): array
	{
		return [
			self::LONG_TERM => 'Долгосрок',
			self::SHORT_TERM => 'Короткосрок',
		];
	}
}
