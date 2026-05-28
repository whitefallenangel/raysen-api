<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingTypeEnum extends AbstractEnum
{
	public const NON_RESIDENTIAL = 1;
	public const RESIDENTIAL_WITH_NON_RESIDENT_PREMISES = 2;
	public const BUSINESS_CENTER = 3;
	public const MANSION = 4;
	public const HOTEL = 5;
	public const SHOPPING_CENTER = 6;
	public const RETAIL_PREMISES = 7;
	public const GENERAL_PURPOSE_PREMISES  = 8;
	public const WAREHOUSE_PREMISES = 9;
	public const INDUSTRIAL_WAREHOUSE_PREMISES = 10;

	public static function labels(): array
	{
		return [
			self::NON_RESIDENTIAL => 'Нежилое здание',
			self::RESIDENTIAL_WITH_NON_RESIDENT_PREMISES  => 'Жилое здание с нежилыми помещениями',
			self::BUSINESS_CENTER => 'Бизнес-центр (МФК)',
			self::MANSION  => 'Особняк',
			self::HOTEL    => 'Гостиница',
			self::SHOPPING_CENTER => 'Торговый центр',
			self::RETAIL_PREMISES  => 'Торговое помещение',
			self::GENERAL_PURPOSE_PREMISES  => 'Свободного назначения (ПСН)',
			self::WAREHOUSE_PREMISES  => 'Складское помещение',
			self::INDUSTRIAL_WAREHOUSE_PREMISES  => 'Производственно-складское помещение',
		];
	}
}