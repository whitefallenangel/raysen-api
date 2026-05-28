<?php

namespace app\enum\Building;

use app\enum\AbstractEnum;

class BuildingOwnershipTypeEnum extends AbstractEnum
{
	public const OWNERSHIP = 2;
	public const SHORT_TERM_RENT = 1;
	public const LONG_TERM_RENT = 1;
	public const PERPETUAL_RENT = 4;
	public const FREE_USE = 3;
	public const NO_RIGHT_REGISTERED = 5;

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
