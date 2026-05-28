<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferCommunalServicesTypeEnum extends AbstractEnum
{
	public const HEATING = 1;
	public const WATER = 2;
	public const ELECTRICITY = 3;

	public static function labels(): array
	{
		return [
			self::HEATING => 'Отопление',
			self::WATER => 'Вода',
			self::ELECTRICITY => 'Электричество',
		];
	}
}
