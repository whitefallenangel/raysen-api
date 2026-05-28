<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotCoverageEnum extends AbstractEnum
{
	public const SOIL = 7;
	public const GRAVEL = 2;
	public const ASPHALT = 1;
	public const CONCRETE_SLABS = 3;
	public const ASPHALT_CHIPS = 6;

	public static function labels(): array
	{
		return [
			self::SOIL => 'Грунт',
			self::GRAVEL => 'Щебень',
			self::ASPHALT => 'Асфальт',
			self::CONCRETE_SLABS => 'Бетонные плиты',
			self::ASPHALT_CHIPS => 'Асфальтовая крошка',
		];
	}
}