<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotCategoryEnum extends AbstractEnum
{
	public const AGRICULTURAL = 6;
	public const SETTLEMENT = 4;
	public const INDUSTRIAL = 5;
	public const PROTECTED = 3;
	public const FOREST = 2;
	public const RESERVE = 1;

	public static function labels(): array
	{
		return [
			self::AGRICULTURAL => 'Земли сельскохозяйственного назначения',
			self::SETTLEMENT => 'Земли населенных пунктов',
			self::INDUSTRIAL => 'Земли промышленности',
			self::PROTECTED  => 'Земли особоохраняемых территорий и объектов',
			self::FOREST  => 'Земли лесного фонда',
			self::RESERVE => 'Земли запаса',
		];
	}
}