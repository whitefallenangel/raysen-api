<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotProjectStageEnum extends AbstractEnum
{
	public const INITIAL_PRE_PROJECT = 1;
	public const GPZU = 2;
	public const PROJECT_DEVELOPED = 3;
	public const AGR_AGO = 4;
	public const EXPERTISE = 5;
	public const RNS = 6;
	public const UNFINISHED_CONSTRUCTION = 7;

	public static function labels(): array
	{
		return [
			self::INITIAL_PRE_PROJECT => 'Начальная/Предпроект',
			self::GPZU => 'ГПЗУ',
			self::PROJECT_DEVELOPED => 'Разработан проект',
			self::AGR_AGO => 'АГР/АГО',
			self::EXPERTISE => 'Экспертиза',
			self::RNS => 'РнС',
			self::UNFINISHED_CONSTRUCTION  => 'Незавершенное строительство',
		];
	}
}