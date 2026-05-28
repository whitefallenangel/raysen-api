<?php

namespace app\enum\Offer;

use app\enum\AbstractEnum;

class OfferOpexTypeEnum extends AbstractEnum
{
	public const BUILDING_MAINTENANCE = 1;
	public const PERIMETER_SECURITY = 2;
	public const GARBAGE_COLLECTION = 3;
	public const TERRITORY_CLEANING = 4;

	public static function labels(): array
	{
		return [
			self::BUILDING_MAINTENANCE => 'Обслуживание здания',
			self::PERIMETER_SECURITY => 'Охрана периметра',
			self::GARBAGE_COLLECTION => 'Вывоз мусора',
			self::TERRITORY_CLEANING => 'Уборка территории',
		];
	}
}
