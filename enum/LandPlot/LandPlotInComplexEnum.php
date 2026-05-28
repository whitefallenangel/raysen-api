<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotInComplexEnum extends AbstractEnum
{
	public const IN_EXISTING = 1;
	public const CREATE_AND_ADD = 2;
	public const NOT_INCLUDED = 3;
	public const CANNOT_IDENTIFY = 4;

	public static function labels(): array
	{
		return [
			self::IN_EXISTING => 'Входит в существующий',
			self::CREATE_AND_ADD => 'Создать новый комплекс и добавить',
			self::NOT_INCLUDED => 'Не входит в комплекс',
			self::CANNOT_IDENTIFY => 'Не могу идентифицировать',
		];
	}
}