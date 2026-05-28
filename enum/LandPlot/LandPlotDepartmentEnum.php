<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class LandPlotDepartmentEnum extends AbstractEnum
{
	public const WAREHOUSE   = 1;
	public const OFFICE = 2;
	public const RETAIL = 3;

	public static function labels(): array
	{
		return [
			self::WAREHOUSE => 'Складской',
			self::OFFICE => 'Офисный',
			self::RETAIL => 'Тороговый',
		];
	}
}