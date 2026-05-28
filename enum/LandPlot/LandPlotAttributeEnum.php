<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotAttributeEnum extends AbstractEnum
{
	public const CRANE_DEVICE = 1;
	public const ELEVATOR_LIFT = 2;
	public const PARKING = 3;
	public const RAILWAY_LINE = 4;
	public const RAILWAY_CRANE = 5;
	public const GANTRY_CRANE = 6;
	public const PAID_ENTRANCE = 7;
	public const NONE = 8;

	public static function labels(): array
	{
		return [
			self::CRANE_DEVICE => 'Крановое устройство',
			self::ELEVATOR_LIFT => 'Лифт/подъемник',
			self::PARKING => 'Парковка',
			self::RAILWAY_LINE => 'Ж/Д ветка',
			self::RAILWAY_CRANE => 'Ж/Д кран',
			self::GANTRY_CRANE => 'Козловой кран',
			self::PAID_ENTRANCE => 'Платный въезд',
			self::NONE => 'Отсутствуют',
		];
	}
}