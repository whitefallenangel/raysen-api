<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotBuildingsPresenceEnum extends AbstractEnum
{
	public const NOT_AVAILABLE = 1;
	public const DEMOLITION_REQUIRED = 2;
	public const RECONSTRUCTION = 3;

	public static function labels(): array
	{
		return [
			self::NOT_AVAILABLE => 'Отстутствуют',
			self::DEMOLITION_REQUIRED => 'Требуется снос',
			self::RECONSTRUCTION => 'Реконструкция/Кап.ремонт',
		];
	}
}