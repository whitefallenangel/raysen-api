<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotLineEnum extends AbstractEnum
{
	public const FIRST_LINE   = 1;
	public const IN_COURTYARD = 2;

	public static function labels(): array
	{
		return [
			self::FIRST_LINE => 'Первая линия',
			self::IN_COURTYARD => 'В глубине',
		];
	}
}