<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingRealEstateEnum extends AbstractEnum
{
	public const COMMERCIAL = 1;
	public const RESIDENTIAL = 2;

	public static function labels(): array
	{
		return [
			self::COMMERCIAL => 'Коммерческая',
			self::RESIDENTIAL => 'Жилая',
		];
	}
}