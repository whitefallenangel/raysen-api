<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingMainSectionsEnum extends AbstractEnum
{
	public const OFFICE_REAL_ESTATE = 1;
	public const RETAIL_REAL_ESTATE = 2;
	public const WAREHOUSING_REAL_ESTATE = 3;

	public static function labels(): array
	{
		return [
			self::OFFICE_REAL_ESTATE => 'Офисная недвижимость',
			self::RETAIL_REAL_ESTATE => 'Торговая недвижимость',
			self::WAREHOUSING_REAL_ESTATE => 'Складская недвижимость',
		];
	}
}