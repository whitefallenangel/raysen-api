<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotReliefEnum extends AbstractEnum
{
	public const FLAT = 1;
	public const SLOPE = 2;
	public const RAVINE = 3;
	public const HILLS = 4;

	public static function labels(): array
	{
		return [
			self::FLAT => 'Ровный',
			self::SLOPE => 'Уклон',
			self::RAVINE => 'Овраг',
			self::HILLS => 'Холмы',
		];
	}
}