<?php

namespace app\enum\LandPlot;

use app\enum\AbstractEnum;

class LandPlotOwnershipTypeEnum extends AbstractEnum
{
	public const OWNERSHIP = 1;
	public const SHORT_TERM_RENT = 2;
	public const LONG_TERM_RENT = 2;
	public const PERPETUAL_RENT = 2;
	public const FREE_USE = 2;
	public const NO_RIGHT_REGISTERED = 2;

	public static function labels(): array
	{
		return [
			self::OWNERSHIP => 'Собственность',
			self::SHORT_TERM_RENT => 'Аренда (краткосрочная)',
			self::LONG_TERM_RENT => 'Аренда (долгосрочная))',
			self::PERPETUAL_RENT => 'Аренда (бессрочная)',
			self::FREE_USE => 'Безвозмездное пользование',
			self::NO_RIGHT_REGISTERED => 'Право не оформлено',
		];
	}
}
