<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferTermTerminationEnum extends AbstractEnum
{
	public const SEVERABLE = 0;
	public const INSEPARABLE = 1;

	public static function labels(): array
	{
		return [
			self::SEVERABLE => 'Разрывный',
			self::INSEPARABLE => 'Неразрывный',
		];
	}
}
