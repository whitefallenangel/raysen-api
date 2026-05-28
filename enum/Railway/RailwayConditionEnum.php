<?php

namespace app\enum\Railway;

use app\enum\AbstractEnum;

class RailwayConditionEnum extends AbstractEnum
{
	public const WORKING = 1;
	public const REQUIRES_REPAIR = 2;
	public const REQUIRES_MAINTENANCE = 3;

	public static function labels(): array
	{
		return [
			self::WORKING => 'Рабочее',
			self::REQUIRES_REPAIR => 'Требует ремонта',
			self::REQUIRES_MAINTENANCE => 'Требует тех.обслуживания',
		];
	}
}